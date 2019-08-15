<?php

namespace App\Helper\Builder;

use App\Entity\BuienRadar;
use App\Entity\EntityInterface;

/**
 * Class BuienRadarBuilder
 * @package App\Helper\Builder
 */
class BuienRadarBuilder extends AbstractBuilder
{
    const FIELD_LOCATION = 'location';

    /**
     * @param EntityInterface|BuienRadar $object
     * @return array
     */
    protected function createOutput(EntityInterface $object): array
    {
        return [
            self::FIELD_ID => $object->getId(),
            self::FIELD_LOCATION => $object->getLocation(),
        ];
    }

    /**
     * @param EntityInterface|BuienRadar $entity
     * @param \stdClass $object
     * @return EntityInterface|BuienRadar
     */
    protected function createEntity(EntityInterface $entity, \stdClass $object): EntityInterface
    {
        $entity->setLocation($object->{self::FIELD_LOCATION});

        return $entity;
    }

    /**
     * @return string
     */
    protected function getClassname(): string
    {
        return BuienRadar::class;
    }
}