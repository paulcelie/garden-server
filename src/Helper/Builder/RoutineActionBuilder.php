<?php

namespace App\Helper\Builder;

use App\Entity\EntityInterface;
use App\Entity\Group;
use App\Entity\Routine;
use App\Entity\RoutineAction;

/**
 * Class RoutineActionBuilder
 * @package App\Helper\Builder
 */
class RoutineActionBuilder extends AbstractBuilder
{
    const FIELD_ROUTINE_ID = 'routine_id';
    const FIELD_GROUP_ID   = 'group_id';
    const FIELD_DURATION   = 'minutes';
    const FIELD_POSITION   = 'order';

    /**
     * @param EntityInterface|RoutineAction $entity
     * @param \stdClass $object
     * @return EntityInterface|RoutineAction
     */
    protected function createEntity(EntityInterface $entity, \stdClass $object): EntityInterface
    {
        $routine = $this->entityManager->getRepository(Routine::class)->find($object->{self::FIELD_ROUTINE_ID});
        $group = $this->entityManager->getRepository(Group::class)->find($object->{self::FIELD_GROUP_ID});

        $entity->setRoutine($routine);
        $entity->setGroupId($group);
        $entity->setDuration($object->{self::FIELD_DURATION});
        $entity->setPosition($object->{self::FIELD_POSITION});

        return $entity;
    }

    /**
     * @param EntityInterface|RoutineAction $object
     * @return array
     */
    protected function createOutput(EntityInterface $object): array
    {
        return [
            self::FIELD_ID => $object->getId(),
            self::FIELD_ROUTINE_ID => $object->getRoutine()->getId(),
            self::FIELD_GROUP_ID => $object->getGroupId()->getId(),
            self::FIELD_DURATION => $object->getDuration(),
            self::FIELD_POSITION => $object->getPosition(),
        ];
    }

    /**
     * @return string
     */
    protected function getClassname(): string
    {
        return RoutineAction::class;
    }
}