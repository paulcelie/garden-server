<?php

namespace App\Repository;

use App\Entity\HueBridge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HueBridge|null find($id, $lockMode = null, $lockVersion = null)
 * @method HueBridge|null findOneBy(array $criteria, array $orderBy = null)
 * @method HueBridge[]    findAll()
 * @method HueBridge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HueBridgeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HueBridge::class);
    }

    // /**
    //  * @return HueBridge[] Returns an array of HueBridge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HueBridge
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
