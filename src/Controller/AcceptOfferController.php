<?php

namespace App\Controller;

use App\Service\Mercure;
use App\Service\Helper;
use App\Service\CurrentUser;
use App\Entity\Offer;
use App\Entity\SaleOffer;
use App\Entity\PurchaseOffer;
use App\Entity\Purchase;
use App\Entity\BulkPurchaseOffer;
use App\Entity\BulkPurchase;
use App\Entity\AuctionBid;
use App\Entity\Bid;
use App\Entity\Transaction;
use App\Entity\Gain;
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
	private $helper;
	private $mercure;

	public function __construct(EntityManagerInterface $em, CurrentUser $cr, Helper $helper, Mercure $mercure)
	{
		$this->em = $em;
		$this->cr = $cr;
		$this->helper = $helper;
		$this->mercure = $mercure;
	}

	private function refundUser($gain, $currentUser)
	{
		$offer = $gain->getOffer();
		$owner = $offer->getOwner();
		$last_bid = $this->em->getRepository(Bid::class)->findOneBy(['offer' => $offer, 'isActive' => true], ['price' => 'DESC']);
		$fees = $gain->getFees();

		$user = $gain->getUser();
		if ($last_bid->getBidder()->getId() === $user->getId()) {
			$gain->setPaid();
			$owner->setBalance($owner->getBalance() + $fees);
		} else {
			$gain->setRefunded();
			$user->setBalance($user->getBalance() + $fees);
		}

		$bids = $this->em->getRepository(Bid::class)->findBy([
			'bidder' => $user,
			'offer' => $offer,
			'isActive' => true
		], ['date' => 'DESC']);

		try {
			foreach ($bids as $bid) {
				$bid->setInactive();
				$this->em->persist($bid);
			}
			if ($last_bid->getBidder()->getId() === $user->getId()) {
				$this->em->persist($owner);
				$message = "You left the auction and you lost ".$fees." MAD on";
				$this->notifyGainToOwner($offer, $fees);
			} else {
				$this->em->persist($user);
				$message = "You got your money back (".$fees." MAD) because you left the auction";
			}
			$this->em->persist($gain);
			$this->notifyBidUpdatedToUser($user, $offer, $currentUser, $message);
		}catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}
	}

	private function notifyGainToOwner($offer, $gain)
	{
		$owner = $offer->getOwner();
		$notification = new Notification();
		$notification->setUser($owner);
		$notification->setType(0);
		$notification->setReference($offer->getId());
		$notification->setMessage("You gain ".$gain." MAD because the first bidder leaves your auction.");

		try {
			$this->em->persist($notification);
		} catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		$this->mercure->publishNotification($notification);
	}

	private function notifyBidUpdatedToUser($user, $offer, $currentUser, $message = null)
	{
		$notification = new Notification();
		$notification->setUser($user);
		$notification->setType(0);
		$notification->setReference($offer->getId());

		if (null !== $message)
			$notification->setMessage($message.": ".$offer->getTitle());
		else if ($currentUser->getId() === $user->getId())
			$notification->setMessage("Your bid on : ".$offer->getTitle()." has been updated.");
		else
			$notification->setMessage("Someone bid more than you at auction : ".$offer->getTitle().".");

		try {
			$this->em->persist($notification);
		}catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		$this->mercure->publishNotification($notification);
	}

	private function notifyPurchaseAcceptedToBuyer($buyer, $offer, $seller, $weight)
	{
		$notification = new Notification();
		$notification->setUser($buyer);
		$notification->setType(0);
		$notification->setReference($offer->getId());
		$notification->setMessage($seller->getFirstName()." ".$seller->getLastName()." accepted to sell you ".$weight."KG on : ".$offer->getTitle().".");

		try {
			$this->em->persist($notification);
		} catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		$this->mercure->publishNotification($notification);
	}

	private function notifySaleAcceptedToOwner($owner, $offer, $buyer)
	{
		$notification = new Notification();
		$notification->setUser($buyer);
		$notification->setType(0);
		$notification->setReference($offer->getId());
		$notification->setMessage($buyer->getFirstName()." ".$buyer->getLastName()." accepted to buy from you : ".$offer->getTitle().".");

		try {
			$this->em->persist($notification);
		} catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		$this->mercure->publishNotification($notification);
	}

	private function handleSaleOffer($offer, $user)
	{
		$total = $offer->getPrice() * $offer->getWeight();
		$fees = $this->helper->getFees($total, 'feesTransactionStatic', 'feesTransactionDynamic');
		if ($user->getBalance() < ($total + $fees))
			throw new HttpException(406, 'Insufficient balance.');
		$user->setBalance($user->getBalance() - ($total + $fees));

		$gain = new Gain();
		$gain->setOffer($offer);
		$gain->setUser($user);
		$gain->setFees($fees);
		$gain->setType(Gain::NOTCREATOR);
		$gain->setPaid();

		$transaction = new Transaction();
		$transaction->setBuyer($user);
		$transaction->setSeller($offer->getOwner());
		$transaction->setTotal($total);
		$transaction->setOffer($offer);
		$transaction->setGain($gain);
		$transaction->setPaid();

		$offer->setBuyer($user);
		$offer->setInactive();

		try {
			$this->em->persist($transaction);
			$this->em->persist($gain);
			$this->em->persist($offer);
			$this->em->persist($user);
			$this->em->flush();
		}catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		$this->notifySaleAcceptedToOwner($offer->getOwner(), $offer, $user);

		$extras['transaction_id'] = $transaction->getId();
		return $extras;
	}

	private function handlePurchase($offer, $user, $request, $class, $extra_param)
	{
		$data = json_decode($request->getContent(), true);
		if (!array_key_exists('weight', $data))
			throw new HttpException(406, 'Weight not found.');
		$weight = $data['weight'];
		if ($weight > $offer->getWeight())
			throw new HttpException(406, 'Weight must be lower than or equal to '.$offer->getWeight().'KG.');
		$min = $offer->getMin();
		if (null === $min)
			$min = 1;
		if ($weight < $min)
			throw new HttpException(406, 'Weight must be greater than or equal to '.$min.'KG.');

		$havePending = $this->em->getRepository($class)->findOneBy([
			'offer' => $offer,
			'seller' => $user,
			'accepted' => false
		]);
		if (null !== $havePending)
			throw new HttpException(406, 'You already have a pending status in this offer');

		$purchase = new $class;
		$purchase->setWeight($weight);
		$purchase->setPrice($offer->getPrice());
		$purchase->setOffer($offer);
		$purchase->setSeller($user);

		try {
			$this->em->persist($purchase);
			$this->em->flush();
		} catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		$this->notifyPurchaseAcceptedToBuyer($offer->getOwner(), $offer, $user, $weight);

		$extras[$extra_param] = $purchase->getId();
		return $extras;
	}

	private function handlePurchaseOffer($offer, $user, $request)
	{
		return $this->handlePurchase($offer, $user, $request, Purchase::class, 'purchase_id');
	}

	private function handleBulkPurchaseOffer($offer, $user, $request)
	{
		return $this->handlePurchase($offer, $user, $request, BulkPurchase::class, 'bulk_purchase_id');
	}

	private function checkIfBidder($offer, $user)
	{
		$gain = $this->em->getRepository(Gain::class)->findOneBy([
			'offer' => $offer,
			'user' => $user,
			'paid' => false,
			'refunded' => false
		], ['date' => 'DESC']);

		if (null === $gain)
			return false;
		else
			return true;
	}

	private function handleAuctionBid($offer, $user, $request)
	{
		$total = $offer->getPrice() * $offer->getWeight();
		$fees = $offer->getWarranty();
		if (null === $fees)
			$fees = 0;
		$alreadyBidder = $this->checkIfBidder($offer, $user);
		if (false === $alreadyBidder && $user->getBalance() < $fees)
			throw new HttpException(406, 'Insufficient balance, you must pay warranty of Bid : '.$fees);
		$data = json_decode($request->getContent(), true);
		if (!array_key_exists('bid_price', $data))
			throw new HttpException(406, 'bid_price not found.');
		$bid_price = $data['bid_price'];
		$percentage = $this->em->getRepository(Parameter::class)->get('percentageNextBid')->getValue();
		$last_bid = $this->em->getRepository(Bid::class)->findOneBy(['offer' => $offer, 'isActive' => true], ['price' => 'DESC']);
		$total = (null === $last_bid) ? $total + ($total * $percentage) : $last_bid->getPrice() + ($last_bid->getPrice() * $percentage);
		if (!is_numeric($bid_price) || $bid_price < (int)$total)
			throw new HttpException(406, 'bid_price not correct, it must be greater than or equal : '.(int)$total);

		if (null !== $last_bid)
			$this->notifyBidUpdatedToUser($last_bid->getBidder(), $offer, $user);

		if (false === $alreadyBidder) {
			$user->setBalance($user->getBalance() - $fees);

			$gain = new Gain();
			$gain->setOffer($offer);
			$gain->setFees($fees);
			$gain->setUser($user);
		}

		$bid = new Bid();
		$bid->setOffer($offer);
		$bid->setBidder($user);
		$bid->setPrice($bid_price);

		try {
			$this->em->persist($bid);
			if (false === $alreadyBidder) {
				$this->em->persist($gain);
				$this->em->persist($user);
			}
			$this->em->flush();
		}catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		$this->mercure->updateLiveAuction($offer, $user, $bid_price, $percentage);

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
			return $this->handlePurchaseOffer($offer, $user, $request);
		}
		else if ($offer instanceof BulkPurchaseOffer)
		{
			$this->denyAccessUnlessGranted('ROLE_RESELLER');
			return $this->handleBulkPurchaseOffer($offer, $user, $request);
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
	public function acceptOfferAction($id, Request $request)
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

	/**
	 * @Route("/api/auction/{id}/leave", name="leave_auction", methods={"PATCH"}, requirements={"id"="\d+"})
	 */
	public function leaveAuctionAction($id, Request $request)
	{
		$this->denyAccessUnlessGranted('ROLE_BUYER');
		$code = 200;
		$message = 'You left the auction successfully.';
		$extras = null;

		$offer = $this->em->getRepository(AuctionBid::class)->find($id);
		if (null === $offer)
			throw new HttpException(404, 'Auction not found.');
		$user = $this->cr->getCurrentUser($this);

		$gain = $this->em->getRepository(Gain::class)->findOneBy([
			'user' => $user,
			'offer' => $offer,
			'paid' => false,
			'refunded' => false
		]);

		if (null === $gain) {
			$code = 406;
			$message = "You are not an active bidder in this offer.";
		} else {
			try {
				$this->refundUser($gain, $user);
				$this->em->flush();
			}catch (\Exception $ex) {
				throw new HttpException(406, 'Not Acceptable.');
			}
		}

		return $this->json([
			'code' => $code,
			'message' => $message,
		], $code);
	}
}
