<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		$names = array(
			'Construction waste',
			'Electronics',
			'Glass',
			'Hazardous',
			'Metals',
			'Mixed waste products',
			'Organic',
			'Paper and cardboard',
			'Plastics',
			'Rubber',
			'Textiles',
			'Wood'
		);
		foreach ($names as $name)
		{
			$category = new Category();
			$category->setLabel($name);
			$manager->persist($category);
		}
		$manager->flush();
	}
}
