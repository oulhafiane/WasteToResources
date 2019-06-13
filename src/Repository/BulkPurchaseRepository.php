<?php

namespace App\Repository;

use App\Entity\BulkPurchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BulkPurchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method BulkPurchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method BulkPurchase[]    findAll()
 * @method BulkPurchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BulkPurchaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BulkPurchase::class);
    }

    // /**
    //  * @return BulkPurchase[] Returns an array of BulkPurchase objects
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
    public function findOneBySomeField($value): ?BulkPurchase
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
