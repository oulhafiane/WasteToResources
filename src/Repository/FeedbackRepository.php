<?php

namespace App\Repository;

use App\Entity\Feedback;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Feedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feedback[]    findAll()
 * @method Feedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedbackRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

	public function findByUser($user, $page = 1, $limit = 12)
	{
		$qb = $this->createQueryBuilder('n')
			->select('n');
		$qb->where($qb->expr()->eq('n.receiver', $user->getId()))
			->orderBy('n.date', 'desc')
			;
		
		return $this->paginate($qb, $limit, $page);
	}

    // /**
    //  * @return Feedback[] Returns an array of Feedback objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Feedback
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
