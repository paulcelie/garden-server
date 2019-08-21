<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AbstractEntityManagerService
 * @package App\Service
 */
abstract class AbstractEntityManagerService
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @return string
     */
    abstract protected function getEntityClass(): string ;

    /**
     * BuienRadar constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getEntity()
    {
        $entities = $this->entityManager->getRepository($this->getEntityClass())->findAll();
        foreach ($entities as $entity) {
            return $entity;
        }

        throw new \Exception('Entity ' . $this->getEntityClass() . ' not found');
    }
}