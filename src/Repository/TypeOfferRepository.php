<?php

namespace App\Repository;

use App\Entity\TypeOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOffer[]    findAll()
 * @method TypeOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeOfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeOffer::class);
    }

    // /**
    //  * @return TypeOffer[] Returns an array of TypeOffer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeOffer
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
