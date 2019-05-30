<?php

namespace App\Repository;

use App\Entity\OffreVente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OffreVente|null find($id, $lockMode = null, $lockVersion = null)
 * @method OffreVente|null findOneBy(array $criteria, array $orderBy = null)
 * @method OffreVente[]    findAll()
 * @method OffreVente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreVenteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OffreVente::class);
    }

    // /**
    //  * @return OffreVente[] Returns an array of OffreVente objects
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
    public function findOneBySomeField($value): ?OffreVente
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
