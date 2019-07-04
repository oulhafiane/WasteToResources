<?php

namespace App\Repository;

use App\Entity\SaleOffer;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SaleOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaleOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaleOffer[]    findAll()
 * @method SaleOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleOfferRepository extends AbstractOfferRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SaleOffer::class);
    }

    // /**
    //  * @return SaleOffer[] Returns an array of SaleOffer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SaleOffer
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
