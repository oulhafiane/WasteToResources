<?php

namespace App\Repository;

use App\Entity\AchatGros;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AchatGros|null find($id, $lockMode = null, $lockVersion = null)
 * @method AchatGros|null findOneBy(array $criteria, array $orderBy = null)
 * @method AchatGros[]    findAll()
 * @method AchatGros[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AchatGrosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AchatGros::class);
    }

    // /**
    //  * @return AchatGros[] Returns an array of AchatGros objects
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
    public function findOneBySomeField($value): ?AchatGros
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
