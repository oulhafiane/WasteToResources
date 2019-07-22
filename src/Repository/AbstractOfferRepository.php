<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

abstract class AbstractOfferRepository extends AbstractRepository
{
	public function findOffers($page = 1, $limit = 12, $category = null)
	{
		$qb = $this->createQueryBuilder('s')
			->select('s')
			->orderBy('s.startDate', 'desc')
			;

		if (null !== $category && is_numeric($category)) {
			$qb->where('s.category = ?1')
				->setParameter(1, $category);
		}

		return $this->paginate($qb, $limit, $page);
	}

	public function findByUser($user, $page = 1, $limit = 12)
	{
		$qb = $this->createQueryBuilder('n')
			->select('n');
		$qb->where($qb->expr()->eq('n.owner', $user->getId()))
			->orderBy('n.startDate', 'desc')
			;

		return $this->paginate($qb, $limit, $page);
	}
}
