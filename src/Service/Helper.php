<?php

namespace App\Service;

use App\Entity\SaleOffer;
use App\Entity\PurchaseOffer;
use App\Entity\BulkPurchaseOffer;
use App\Entity\AuctionBid;
use App\Entity\Parameter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Helper
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}


	public function getFees($total, $offerStaticParam, $offerDynamicParam)
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

	public function getOfferFees($offer)
	{
		$fees = null;
		if ($offer instanceof SaleOffer) {
			$fees = $this->getFees($offer->getTotal(), 'feesSaleOfferStatic', 'feesSaleOfferDynamic');
		} else if ($offer instanceof PurchaseOffer) {
			$fees = $this->getFees($offer->getTotal(), 'feesPurchaseOfferStatic', 'feesPurchaseOfferDynamic');
		} else if ($offer instanceof BulkPurchaseOffer) {
			$fees = $this->getFees($offer->getTotal(), 'feesBulkPurchaseOfferStatic', 'feesBulkPurchaseOfferDynamic');
		} else if ($offer instanceof AuctionBid) {
			$period = $offer->getPeriod();
			switch ($period) {
			case 1:
				$fees = $this->helper->getFees($total, 'feesMediumAuctionBidStatic', 'feesMediumAuctionBidDynamic');
				break;
			case 2:
				$fees = $this->helper->getFees($total, 'feesLargeAuctionBidStatic', 'feesLargeAuctionBidDynamic');
				break;
			default:
				$fees = $this->helper->getFees($total, 'feesSmallAuctionBidStatic', 'feesSmallAuctionBidDynamic');
				break;
			}
		} else {
			throw new HttpException(500, 'Cannot get fees details.');
		}

		return $fees;
	}

	public function getRealPeriodAuction($auction)
	{
		if (!($auction instanceof AuctionBid))
			return $em->getRepository(Parameter::class)->findOneBy(['param' => 'periodOffer']);
		$period = $auction->getPeriod();
		switch ($period) {
		case 1:
			$realPeriod = $em->getRepository(Parameter::class)->findOneBy(['param' => 'mediumPeriodAuctionBid']);
			$fees = $this->helper->getFees($total, 'feesMediumAuctionBidStatic', 'feesMediumAuctionBidDynamic');
			break;
		case 2:
			$realPeriod = $em->getRepository(Parameter::class)->findOneBy(['param' => 'largePeriodAuctionBid']);
			$fees = $this->helper->getFees($total, 'feesLargeAuctionBidStatic', 'feesLargeAuctionBidDynamic');
			break;
		default:
			$realPeriod = $em->getRepository(Parameter::class)->findOneBy(['param' => 'smallPeriodAuctionBid']);
			$fees = $this->helper->getFees($total, 'feesSmallAuctionBidStatic', 'feesSmallAuctionBidDynamic');
			break;
		}

		return $realPeriod;
	}

	public function getTransactionEtat($transaction)
	{
		if (true === $transaction->isCompleted() && false === $transaction->isCanceled())
			$etat = 2;
		else if (false === $transaction->isCompleted() && true === $transaction->isCanceled())
			$etat = -1;
		else if ($transaction->isCompleted() === $transaction->isCanceled() && $transaction->isCanceled() === true)
			$etat = -2;
		else if (true === $transaction->isPaid())
			$etat = 1;
		else if (false === $transaction->isPaid())
			$etat = 0;
		else
			$etat = -2;

		return $etat;
	}
}
