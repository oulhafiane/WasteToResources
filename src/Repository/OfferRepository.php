<?php

namespace App\Repository;

use App\Entity\Offer;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends AbstractOfferRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Offer::class);
    }

	public function getEndedOffers()
	{
		return $this->createQueryBuilder('o')
			->andWhere('o.isActive = true')
			->andWhere('o.endDate < :today')
			->setParameter('today', new \DateTime(date("Y-m-d H:i:s")))
			->getQuery()
			->getResult()
		;
	}

    // /**
    //  * @return Offer[] Returns an array of Offer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Offer
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
