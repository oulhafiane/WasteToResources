<?php

namespace App\DataFixtures;

use App\Entity\Parameter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ParametersFixtures extends Fixture
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
			'feesAuctionBidStatic' => 800,
			'feesSaleOfferDynamic' => 0.02,
			'feesPurchaseOfferDynamic' => 0.02,
			'feesBulkPurchaseOfferDynamic' => 0.035,
			'feesAuctionBidDynamoic' => 0.035,
			'feesBid' => 500,
			'percentageNextBid' => 0.01
		);

		foreach($params as $key=>$value) {
			$param = new Parameter($key, $value);
			$manager->persist($param);
		}

        $manager->flush();
    }
}
