<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

abstract class AbstractRepository extends ServiceEntityRepository
{
    protected function paginate(QueryBuilder $qb, $limit = 12, $page = 1)
    {
        if (0 >= $limit || 0 >= $page) {
            throw new \LogicException('page must be greater than 0.');
        }
        
        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
		if (!is_numeric($page))
			$page = 1;
		if (!is_numeric($limit))
			$limit = 12;
		if ($limit > 50)
			$limit = 50;
        $pager->setMaxPerPage((int) $limit);
        $pager->setCurrentPage($page);
        
        return $pager;
    }

    public function findOffer($page = 1, $limit = 12, $category = null) {
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
}
