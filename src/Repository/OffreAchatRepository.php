<?php

namespace App\Repository;

use App\Entity\OffreAchat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OffreAchat|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreAchat|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreAchat[]    findAll()
 * @method OffreAchat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreAchatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OffreAchat::class);
    }

    // /**
    //  * @return OffreAchat[] Returns an array of OffreAchat objects
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
    public function findOneBySomeField($value): ?OffreAchat
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
