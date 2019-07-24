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
