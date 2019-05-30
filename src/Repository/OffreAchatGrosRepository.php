<?php

namespace App\Repository;

use App\Entity\OffreAchatGros;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OffreAchatGros|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreAchatGros|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreAchatGros[]    findAll()
 * @method OffreAchatGros[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreAchatGrosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OffreAchatGros::class);
    }

    // /**
    //  * @return OffreAchatGros[] Returns an array of OffreAchatGros objects
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
    public function findOneBySomeField($value): ?OffreAchatGros
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
