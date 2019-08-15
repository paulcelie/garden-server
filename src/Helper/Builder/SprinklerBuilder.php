<?php

namespace App\Helper\Builder;

use App\Entity\EntityInterface;
use App\Entity\Group;
use App\Entity\Sprinkler;

/**
 * Class SprinklerBuilder
 * @package App\Helper\Builder
 */
class SprinklerBuilder extends AbstractBuilder
{
    const FIELD_GROUP_ID   = 'group_id';
    const FIELD_GROUP_NAME = 'group_name';
    const FIELD_NAME       = 'name';
    const FIELD_X          = 'x';
    const FIELD_Y          = 'y';

    /**
     * @param Sprinkler|EntityInterface $object
     * @return array
     */
    protected function createOutput(EntityInterface $object): array
    {
        return [
            self::FIELD_ID => $object->getId(),
            self::FIELD_NAME => $object->getName(),
            self::FIELD_GROUP_ID => $object->getGroupId()->getId(),
            self::FIELD_GROUP_NAME => $object->getGroupId()->getName(),
            self::FIELD_X => $object->getX(),
            self::FIELD_Y => $object->getY(),
        ];
    }

    /**
     * @param EntityInterface|Sprinkler $entity
     * @param \stdClass $object
     * @return EntityInterface
     */
    protected function createEntity(EntityInterface $entity, \stdClass $object): EntityInterface
    {
        $group = $this->entityManager->getRepository(Group::class)->find($object->{self::FIELD_GROUP_ID});

        $entity->setName($object->{self::FIELD_NAME});
        $entity->setGroupId($group);
        $entity->setX($object->{self::FIELD_X});
        $entity->setY($object->{self::FIELD_Y});

        return $entity;
    }

    /**
     * @return string
     */
    protected function getClassname(): string
    {
        return Sprinkler::class;
    }
}