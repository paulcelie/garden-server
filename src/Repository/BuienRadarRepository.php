<?php

namespace App\Repository;

use App\Entity\BuienRadar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BuienRadar|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuienRadar|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuienRadar[]    findAll()
 * @method BuienRadar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuienRadarRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BuienRadar::class);
    }

    // /**
    //  * @return BuienRadar[] Returns an array of BuienRadar objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BuienRadar
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
