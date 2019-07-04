<?php

namespace App\Repository;

use App\Entity\BulkPurchaseOffer;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BulkPurchaseOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method BulkPurchaseOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method BulkPurchaseOffer[]    findAll()
 * @method BulkPurchaseOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BulkPurchaseOfferRepository extends AbstractOfferRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BulkPurchaseOffer::class);
    }

    // /**
    //  * @return BulkPurchaseOffer[] Returns an array of BulkPurchaseOffer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BulkPurchaseOffer
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
