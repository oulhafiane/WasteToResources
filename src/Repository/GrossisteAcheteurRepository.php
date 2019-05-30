<?php

namespace App\Repository;

use App\Entity\GrossisteAcheteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GrossisteAcheteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrossisteAcheteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrossisteAcheteur[]    findAll()
 * @method GrossisteAcheteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrossisteAcheteurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GrossisteAcheteur::class);
    }

    // /**
    //  * @return GrossisteAcheteur[] Returns an array of GrossisteAcheteur objects
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
    public function findOneBySomeField($value): ?GrossisteAcheteur
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
