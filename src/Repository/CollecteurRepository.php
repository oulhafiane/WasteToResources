<?php

namespace App\Repository;

use App\Entity\Collecteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Collecteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Collecteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Collecteur[]    findAll()
 * @method Collecteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollecteurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Collecteur::class);
    }

    // /**
    //  * @return Collecteur[] Returns an array of Collecteur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Collecteur
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
