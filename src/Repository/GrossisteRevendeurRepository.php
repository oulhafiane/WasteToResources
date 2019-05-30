<?php

namespace App\Repository;

use App\Entity\GrossisteRevendeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrossisteRevendeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrossisteRevendeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrossisteRevendeur[]    findAll()
 * @method GrossisteRevendeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrossisteRevendeurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrossisteRevendeur::class);
    }

    // /**
    //  * @return GrossisteRevendeur[] Returns an array of GrossisteRevendeur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GrossisteRevendeur
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
