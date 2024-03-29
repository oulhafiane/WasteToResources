<?php

namespace App\Repository;

use App\Entity\Message;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

	public function findByUser($sender, $receiver, $page = 1, $limit = 12)
	{
		$qb = $this->createQueryBuilder('n')
			->select('n');
		$qb->where($qb->expr()->andX(
				$qb->expr()->eq('n.receiver', $receiver->getId()),
				$qb->expr()->eq('n.sender', $sender->getId())
			))
			->orWhere($qb->expr()->andX(
				$qb->expr()->eq('n.receiver', $sender->getId()),
				$qb->expr()->eq('n.sender', $receiver->getId())
			))
			->orderBy('n.date', 'desc')
			;
		
		return $this->paginate($qb, $limit, $page);
	}

	public function getCountNotSeenByUser($receiver)
	{
		$qb = $this->createQueryBuilder('n')
			->select('count(n)');
		$qb->where($qb->expr()->eq('n.receiver', $receiver->getId()))
			->andWhere('n.seen = 0');

		return $qb->getQuery()
			->getSingleScalarResult();
	}

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
