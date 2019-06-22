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
			'feesBidStatic' => 500,
			'feesSaleOfferDynamic' => 2.0,
			'feesPurchaseOfferDynamic' => 2.0,
			'feesBulkPurchaseOfferDynamic' => 3.5,
			'feesAuctionBidDynamoic' => 3.5,
			'feesBidDynamic' => 2.5,
		);

		foreach($params as $key=>$value) {
			$param = new Parameter($key, $value);
			$manager->persist($param);
		}

        $manager->flush();
    }
}
