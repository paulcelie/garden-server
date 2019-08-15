<?php


namespace App\Helper\Builder;

use App\Entity\EntityInterface;
use App\Entity\HueBridge;

/**
 * Class HueBridgeBuilder
 * @package App\Helper\Builder
 */
class HueBridgeBuilder extends AbstractBuilder
{
    const FIELD_IP       = 'ip';
    const FIELD_USERNAME = 'username';

    /**
     * @param EntityInterface|HueBridge $entity
     * @param \stdClass $object
     * @return EntityInterface
     */
    protected function createEntity(EntityInterface $entity, \stdClass $object): EntityInterface
    {
        $entity->setIp($object->{self::FIELD_IP});
        $entity->setUsername($object->{self::FIELD_USERNAME});

        return $entity;
    }

    /**
     * @param EntityInterface|HueBridge $object
     * @return array
     */
    protected function createOutput(EntityInterface $object): array
    {
        return [
            self::FIELD_ID => $object->getId(),
            self::FIELD_IP => $object->getIp(),
            self::FIELD_USERNAME => $object->getUsername(),
        ];
    }

    /**
     * @return string
     */
    protected function getClassname(): string
    {
        return HueBridge::class;
    }
}