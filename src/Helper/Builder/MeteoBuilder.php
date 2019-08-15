<?php

namespace App\Helper\Builder;

use App\Entity\EntityInterface;
use App\Entity\Meteo;

/**
 * Class MeteoBuilder
 * @package App\Helper\Builder
 */
class MeteoBuilder extends AbstractBuilder
{
    const FIELD_API_KEY = 'api_key';
    const FIELD_LATITUDE = 'latitude';
    const FIELD_LONGITUDE = 'longitude';

    /**
     * @param EntityInterface|Meteo $entity
     * @param \stdClass $object
     * @return EntityInterface
     */
    protected function createEntity(EntityInterface $entity, \stdClass $object): EntityInterface
    {
        $entity->setApiKey($object->{self::FIELD_API_KEY});
        $entity->setLatitude($object->{self::FIELD_LATITUDE});
        $entity->setLongitude($object->{self::FIELD_LONGITUDE});

        return $entity;
    }

    /**
     * @param EntityInterface|Meteo $object
     * @return array
     */
    protected function createOutput(EntityInterface $object): array
    {
        return [
            self::FIELD_ID => $object->getId(),
            self::FIELD_API_KEY => $object->getApiKey(),
            self::FIELD_LATITUDE => $object->getLatitude(),
            self::FIELD_LONGITUDE => $object->getLongitude(),
        ];
    }

    /**
     * @return string
     */
    protected function getClassname(): string
    {
        return Meteo::class;
    }
}