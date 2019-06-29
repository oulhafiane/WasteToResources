<?php

namespace App\Repository;

use App\Entity\OnHold;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OnHold|null find($id, $lockMode = null, $lockVersion = null)
 * @method OnHold|null findOneBy(array $criteria, array $orderBy = null)
 * @method OnHold[]    findAll()
 * @method OnHold[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OnHoldRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OnHold::class);
    }

//    /**
//     * @return OnHold[] Returns an array of OnHold objects
//     */
/*    public function getOnHolds($id_user)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('o.owner_id = :val')
            ->setParameter('val', $id_user)
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
*/ 

    /*
    public function findOneBySomeField($value): ?OnHold
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