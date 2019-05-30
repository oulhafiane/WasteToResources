<?php

namespace App\Repository;

use App\Entity\OffreEnchere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OffreEnchere|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreEnchere|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreEnchere[]    findAll()
 * @method OffreEnchere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreEnchereRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OffreEnchere::class);
    }

    // /**
    //  * @return OffreEnchere[] Returns an array of OffreEnchere objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OffreEnchere
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
