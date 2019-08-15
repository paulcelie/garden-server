<?php

namespace App\Helper\Builder;

use App\Entity\EntityInterface;
use App\Entity\Routine;
use App\Entity\RoutineCondition;

/**
 * Class RoutineConditionBuilder
 * @package App\Helper\Builder
 */
class RoutineConditionBuilder extends AbstractBuilder
{
    const FIELD_ROUTINE_ID = 'routine_id';
    const FIELD_TYPE = 'type';
    const FIELD_MILLIMETER = 'mm';

    /**
     * @param EntityInterface|RoutineCondition $object
     * @return array
     */
    protected function createOutput(EntityInterface $object): array
    {
        return [
            self::FIELD_ID => $object->getId(),
            self::FIELD_ROUTINE_ID => $object->getRoutine()->getId(),
            self::FIELD_TYPE => $object->getType(),
            self::FIELD_MILLIMETER => $object->getMilimeter(),
        ];
    }

    /**
     * @param EntityInterface|RoutineCondition $entity
     * @param \stdClass $object
     * @return EntityInterface|RoutineCondition
     */
    protected function createEntity(EntityInterface $entity, \stdClass $object): EntityInterface
    {
        $routine = $this->entityManager->getRepository(Routine::class)->find($object->{self::FIELD_ROUTINE_ID});
        $entity->setRoutine($routine);
        $entity->setType($object->{self::FIELD_TYPE});
        $entity->setMilimeter($object->{self::FIELD_MILLIMETER});

        return $entity;
    }

    /**
     * @return string
     */
    protected function getClassname(): string
    {
        return RoutineCondition::class;
    }
}