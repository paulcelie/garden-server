<?php

namespace App\Helper\Builder;

use App\Entity\EntityInterface;

/**
 * Interface BuilderInterface
 * @package App\Helper\Builder
 */
interface BuilderInterface
{
    /**
     * @param EntityInterface $object
     * @return array
     */
    public function buildOutput(EntityInterface $object): array;

    /**
     * @param \stdClass $object
     * @param EntityInterface $entity
     * @return EntityInterface
     */
    public function buildEntity(\stdClass $object, EntityInterface $entity): EntityInterface;
}