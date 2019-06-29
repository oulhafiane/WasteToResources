<?php

namespace App\Controller;

use App\Service\CurrentUser;
use App\Entity\Offer;
use App\Entity\SaleOffer;
use App\Entity\PurchaseOffer;
use App\Entity\BulkPurchaseOffer;
use App\Entity\AuctionBid;
use App\Entity\Bid;
use App\Entity\Transaction;
use App\Entity\OnHold;
use App\Entity\Parameter;
use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class AcceptOfferController extends AbstractController
{
	private $em;
	private $cr;

	public function __construct(EntityManagerInterface $em, CurrentUser $cr)
	{
		$this->em = $em;
		$this->cr = $cr;
	}

	private function getSaleOfferFees($total, $offerStaticParam, $offerDynamicParam)
	{
		$fees = null;
		$isStatic = $this->em->getRepository(Parameter::class)->get('feesStatic')->getValue();
		if (1.0 === $isStatic)
			$fees = $this->em->getRepository(Parameter::class)->get($offerStaticParam)->getValue();
		else if (0.0 === $isStatic)
			$fees = $this->em->getRepository(Parameter::class)->get($offerDynamicParam)->getValue();
		if (null !== $fees && is_numeric($fees)) {
			if (0.0 === $isStatic)
				return ($total * $fees);
			return $fees;
		}
		throw new HttpException(500, 'Cannot get fees details.');
	}

	private function handleSaleOffer($offer, $user)
	{
		$total = $offer->getPrice() * $offer->getWeight();
		$fees = $this->getSaleOfferFees($total, 'feesSaleOfferStatic', 'feesSaleOfferDynamic');
		if ($user->getBalance() < ($total + $fees))
			throw new HttpException(406, 'Insufficient balance.');
		$user->setBalance($user->getBalance() - ($total + $fees));

		$transaction = new Transaction();
		$transaction->setBuyer($user);
		$transaction->setSeller($offer->getOwner());
		$transaction->setTotal($total);
		$transaction->setOffer($offer);

		$onHold = new OnHold();
		$onHold->setOffer($offer);
		$onHold->setFees($fees);
		$onHold->setUser($user);


		$offer->setBuyer($user);
		$offer->setIsActive(False);

		try {
			$this->em->persist($transaction);
			$this->em->persist($onHold);
			$this->em->persist($offer);
			$this->em->persist($user);
			$this->em->flush();
		}catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		$extras['transaction_id'] = $transaction->getId();
		return $extras;
	}

	private function refundUser($bid)
	{
		$onhold = $bid->getOnHold();
		$onhold->setRefunded();

		$user = $onhold->getUser();
		$user->setBalance($user->getBalance() + $onhold->getFees());

		$notification = new Notification();
		$notification->setUser($user);
		$notification->setType(0);
		$notification->setReference($bid->getOffer()->getId());
		$notification->setMessage("Your bid on : ".$bid->getOffer()->getTitle()." has been canceled.");

		try {
			$this->em->persist($user);
			$this->em->persist($onhold);
			$this->em->persist($notification);
		}catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}
	}

	private function handlePurchaseOffer($offer, $user)
	{
		
	}

	private function handleBulkPurchaseOffer($offer, $user)
	{

	}

	private function handleAuctionBid($offer, $user, $request)
	{
		$total = $offer->getPrice() * $offer->getWeight();
		$fees = $this->getSaleOfferFees($total, 'feesAuctionBidStatic', 'feesAuctionBidDynamic');
		if ($user->getBalance() < $fees)
			throw new HttpException(406, 'Insufficient balance, you must pay fees of Bid : '.$fees);
		$last_bid = $this->em->getRepository(Bid::class)->findOneBy(['offer' => $offer], ['price' => 'DESC']);
		$data = json_decode($request->getContent(), true);
		if (!array_key_exists('bid_price', $data))
			throw new HttpException(406, 'bid_price not found.');
		$bid_price = $data['bid_price'];
		$percentage = $this->em->getRepository(Parameter::class)->get('percentageNextBid')->getValue();
		$total = (null === $last_bid) ? $total + ($total * $percentage) : $last_bid->getPrice() + ($last_bid->getPrice() * $percentage);
		if (!is_numeric($bid_price) || $bid_price <= $total)
			throw new HttpException(406, 'bid_price not correct, it must be greater than : '.(int)$total);

		if (null !== $last_bid)
			$this->refundUser($last_bid);
		$user->setBalance($user->getBalance() - $fees);

		$bid = new Bid();
		$bid->setOffer($offer);
		$bid->setBidder($user);
		$bid->setPrice($bid_price);

		$onHold = new OnHold();
		$onHold->setBid($bid);
		$onHold->setFees($fees);
		$onHold->setUser($user);

		try {
			$this->em->persist($bid);
			$this->em->persist($onHold);
			$this->em->persist($user);
			$this->em->flush();
		}catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		$extras['bid_id'] = $bid->getId();
		return $extras;
	}

	private function acceptOffer($offer, $request)
	{
		$user = $this->cr->getCurrentUser($this);
		if ($offer instanceof SaleOffer)
		{
			$this->denyAccessUnlessGranted('ROLE_RESELLER');
			return $this->handleSaleOffer($offer, $user);
		}
		else if ($offer instanceof PurchaseOffer)
		{
			$this->denyAccessUnlessGranted('ROLE_PICKER');
			return $this->handlePurchaseOffer($offer, $user);
		}
		else if ($offer instanceof BulkPurchaseOffer)
		{
			$this->denyAccessUnlessGranted('ROLE_RESELLER');
			return $this->handleBulkPurchaseOffer($offer, $user);
		}
		else if ($offer instanceof AuctionBid)
		{
			$this->denyAccessUnlessGranted('ROLE_BUYER');
			return $this->handleAuctionBid($offer, $user, $request);
		}
		else
			throw $this->createAccessDeniedException();
	}

	/**
	 * @Route("/api/offers/{id}/accept", name="accept_offer", methods={"PATCH"}, requirements={"id"="\d+"})
	 */
	public function acceptSale($id, Request $request)
	{
		$code = 200;
		$message = 'Offer accepted successfully.';
		$extras = null;

		$offer = $this->em->getRepository(Offer::class)->find($id);
		if (null === $offer)
			throw new HttpException(404, 'Offer not found.');
		if ($offer->getEndDate() > new \DateTime() && $offer->getIsActive() === True)
			$extras = $this->acceptOffer($offer, $request);
		else
		{
			$code = 406;
			$message = "Offer not active.";
		}

		return $this->json([
			'code' => $code,
			'message' => $message,
			'extras' => $extras
		], $code);
	}
}
