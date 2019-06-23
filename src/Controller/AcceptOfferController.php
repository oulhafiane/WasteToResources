<?php

namespace App\Controller;

use App\Service\CurrentUser;
use App\Entity\Offer;
use App\Entity\SaleOffer;
use App\Entity\PurchaseOffer;
use App\Entity\BulkPurchaseOffer;
use App\Entity\AuctionBid;
use App\Entity\Transaction;
use App\Entity\OnHold;
use App\Entity\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

	private function getSaleOfferFees($total)
	{
		$fees = null;
		$isStatic = $this->em->getRepository(Parameter::class)->get('feesStatic')->getValue();
		if ($isStatic === 1.0)
			$fees = $this->em->getRepository(Parameter::class)->get('feesSaleOfferStatic')->getValue();
		else if ($isStatic === 0.0)
			$fees = $this->em->getRepository(Parameter::class)->get('feesSaleOfferDynamic')->getValue();
		if ($fees !== null && is_numeric($fees)) {
			if ($isStatic === 0.0)
				return ($total * $fees);
			return $fees;
		}
		throw new HttpException(500, 'Cannot get fees details.');
	}

	private function handleSaleOffer($offer, $user)
	{
		$total = $offer->getPrice() * $offer->getWeight();
		$fees = $this->getSaleOfferFees($total);
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
			throw new HttpException(406, 'Unauthorized.');
		}

		$extras['transaction_id'] = $transaction->getId();
		return $extras;
	}

	private function acceptOffer($offer)
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
		}
		else if ($offer instanceof BulkPurchaseOffer)
		{
			$this->denyAccessUnlessGranted('ROLE_RESELLER');
		}
		else if ($offer instanceof AuctionBid)
		{
			$this->denyAccessUnlessGranted('ROLE_BUYER');
		}
		else
			throw $this->createAccessDeniedException();
	}

	/**
	 * @Route("/api/offers/{id}/accept", name="accept_offer", methods={"PATCH"}, requirements={"id"="\d+"})
	 */
	public function acceptSale($id)
	{
		$code = 200;
		$message = 'Offer accepted successfully.';
		$extras = null;

		$offer = $this->em->getRepository(Offer::class)->find($id);
		if (null === $offer)
			throw new HttpException(404, 'Offer not found.');
		if ($offer->getEndDate() > new \DateTime() && $offer->getIsActive() === True)
			$extras = $this->acceptOffer($offer);
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
