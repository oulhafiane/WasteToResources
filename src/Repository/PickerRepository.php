<?php

namespace App\Repository;

use App\Entity\Picker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Picker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picker[]    findAll()
 * @method Picker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PickerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Picker::class);
    }

    // /**
    //  * @return Picker[] Returns an array of Picker objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Picker
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
