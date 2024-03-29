<?php

namespace App\Repository;

use App\Entity\AuctionBid;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AuctionBid|null find($id, $lockMode = null, $lockVersion = null)
 * @method AuctionBid|null findOneBy(array $criteria, array $orderBy = null)
 * @method AuctionBid[]    findAll()
 * @method AuctionBid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuctionBidRepository extends AbstractOfferRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AuctionBid::class);
    }

    // /**
    //  * @return AuctionBid[] Returns an array of AuctionBid objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AuctionBid
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
