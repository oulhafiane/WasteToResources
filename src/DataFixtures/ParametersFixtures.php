<?php

namespace App\DataFixtures;

use App\Entity\Parameter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ParametersFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
		$params = array(
			'feesStatic' => 1,
			'feesSaleOfferStatic' => 400,
			'feesPurchaseOfferStatic' => 400,
			'feesBulkPurchaseOfferStatic' => 800,
			'feesSmallAuctionBidStatic' => 400,
			'feesMediumAuctionBidStatic' => 600,
			'feesLargeAuctionBidStatic' => 800,
			'feesSaleOfferDynamic' => 0.02,
			'feesPurchaseOfferDynamic' => 0.02,
			'feesBulkPurchaseOfferDynamic' => 0.035,
			'feesSmallAuctionBidDynamic' => 0.015,
			'feesMediumAuctionBidDynamic' => 0.025,
			'feesLargeAuctionBidDynamic' => 0.035,
			'percentageNextBid' => 0.01,
			'handleAllInBulkTransactions' => 0.1,
			'smallPeriodAuctionBid' => 2,
			'mediumPeriodAuctionBid' => 5,
			'largePeriodAuctionBid' => 7,
			'periodOffer' => 30
		);

		foreach($params as $key=>$value) {
			$param = new Parameter($key, $value);
			$manager->persist($param);
		}

        $manager->flush();
    }

	public static function getGroups(): array
	{
		return ['parameters'];
	}
}
