<?php

namespace App\Repository;

use App\Entity\PurchaseOffer;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PurchaseOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchaseOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchaseOffer[]    findAll()
 * @method PurchaseOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseOfferRepository extends AbstractOfferRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PurchaseOffer::class);
    }

    // /**
    //  * @return PurchaseOffer[] Returns an array of PurchaseOffer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PurchaseOffer
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
