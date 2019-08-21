<?php

namespace App\Repository;

use App\Entity\RoutineLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RoutineLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoutineLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoutineLog[]    findAll()
 * @method RoutineLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoutineLogRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RoutineLog::class);
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNext()
    {
        return $this->createQueryBuilder('rl')
            ->innerJoin('rl.routine', 'r')
            ->innerJoin('rl.action', 'a')
            ->where('rl.scheduledAt < :now')
            ->andWhere('rl.status = :pending')
            ->setParameter('now', new \DateTime())
            ->setParameter('pending', RoutineLog::STATUS_PENDING)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

    }

    /**
     * @return RoutineLog[]
     * @throws \Exception
     */
    public function getFutures()
    {
        return $this->createQueryBuilder('r')
            ->where('r.scheduledAt > :now')
            ->setParameter('now', new \DateTime())
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return RoutineLog[] Returns an array of RoutineLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RoutineLog
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
