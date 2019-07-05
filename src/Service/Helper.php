<?php

namespace App\Service;

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

	public function getOfferFees($total, $offerStaticParam, $offerDynamicParam)
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
}
