<?php

namespace App\Repository;

use App\Entity\RoutineCondition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RoutineCondition|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoutineCondition|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoutineCondition[]    findAll()
 * @method RoutineCondition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoutineConditionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RoutineCondition::class);
    }

    // /**
    //  * @return RoutineCondition[] Returns an array of RoutineCondition objects
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
    public function findOneBySomeField($value): ?RoutineCondition
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
