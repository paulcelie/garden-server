<?php

namespace App\Helper\Builder;

use App\Entity\EntityInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class AbstractBuilder
 * @package App\Helper\Builder
 */
abstract class AbstractBuilder implements BuilderInterface
{
    const FIELD_ID = 'id';

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * AbstractBuilder constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param EntityInterface $object
     * @return array
     */
    final public function buildOutput(EntityInterface $object): array
    {
        $this->validateClassname($object);
        return $this->createOutput($object);
    }

    /**
     * @param \stdClass $object
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    final public function buildEntity(\stdClass $object, EntityInterface $entity): EntityInterface
    {
        return $this->createEntity($entity, $object);
    }

    /**
     * @param EntityInterface $object
     * @return array
     */
    abstract protected function createOutput(EntityInterface $object): array ;

    /**
     * @param EntityInterface $entity
     * @param \stdClass $object
     * @return array
     */
    abstract protected function createEntity(EntityInterface $entity, \stdClass $object): EntityInterface ;

    /**
     * @return string
     */
    abstract protected function getClassname(): string;

    /**
     * @param $object
     */
    final private function validateClassname($object) {
        $classname = $this->getClassname();
        if (!$object instanceof $classname) {
            throw new \InvalidArgumentException('Object should be of type ' . $classname);
        }
    }
}