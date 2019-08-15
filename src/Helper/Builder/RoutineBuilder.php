<?php


namespace App\Helper\Builder;

use App\Entity\EntityInterface;
use App\Entity\Routine;
use App\Entity\RoutineAction;
use App\Helper\Factory\BuilderFactory;

/**
 * Class RoutineBuilder
 * @package App\Helper\Builder
 */
class RoutineBuilder extends AbstractBuilder
{
    const FIELD_NAME = 'name';
    const FIELD_DAYS = 'days';
    const FIELD_START_TIME = 'start_time';
    const FIELD_CONDITION_TYPE = 'condition_type';
    const FIELD_CONDITIONS = 'conditions';
    const FIELD_GROUPS = 'groups';

    /**
     * @param EntityInterface|Routine $entity
     * @param \stdClass $object
     * @return EntityInterface|Routine
     */
    protected function createEntity(EntityInterface $entity, \stdClass $object): EntityInterface
    {
        $entity->setName($object->{self::FIELD_NAME});
        $entity->setDays(implode(",", $object->{self::FIELD_DAYS}));
        $entity->setStartTime(new \DateTime($object->{self::FIELD_START_TIME}));
        $entity->setConditionType($object->{self::FIELD_CONDITION_TYPE});

        return $entity;
    }

    /**
     * @param EntityInterface|Routine $object
     * @return array
     */
    protected function createOutput(EntityInterface $object): array
    {
        $output = [
            self::FIELD_ID => $object->getId(),
            self::FIELD_NAME => $object->getName(),
            self::FIELD_DAYS => explode(",", $object->getDays()),
            self::FIELD_START_TIME => $object->getStartTime()->format('H:m'),
            self::FIELD_CONDITION_TYPE => $object->getConditionType(),
            self::FIELD_GROUPS => [],
            self::FIELD_CONDITIONS => [],
        ];

        $groupBuilder = BuilderFactory::create($this->entityManager, RoutineActionBuilder::class);
        foreach ($object->getRoutineActions() as $group) {
            $output[self::FIELD_GROUPS][] = $groupBuilder->buildOutput($group);
        }

        $conditionBuilder = BuilderFactory::create($this->entityManager, RoutineConditionBuilder::class);
        foreach ($object->getRoutineConditions() as $condition) {
            $output[self::FIELD_CONDITIONS][] = $conditionBuilder->buildOutput($condition);
        }

        return $output;
    }

    /**
     * @return string
     */
    protected function getClassname(): string
    {
        return Routine::class;
    }
}