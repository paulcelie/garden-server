<?php

namespace App\Helper\Builder;

use App\Entity\EntityInterface;
use App\Entity\Group;
use App\Helper\Factory\BuilderFactory;

/**
 * Class GroupBuilder
 * @package App\Helper\Builder
 */
class GroupBuilder extends AbstractBuilder
{
    const FIELD_EXTERNAL_ID = 'external_id';
    const FIELD_NAME        = 'name';
    const FIELD_SPRINKLERS  = 'sprinklers';

    /**
     * @param Group|EntityInterface $object
     * @return array
     */
    protected function createOutput(EntityInterface $object): array
    {
        return [
            self::FIELD_ID => $object->getId(),
            self::FIELD_EXTERNAL_ID => $object->getExternalId(),
            self::FIELD_NAME => $object->getName(),
            self::FIELD_SPRINKLERS => $this->getSprinklersOutput($object),
        ];
    }

    /**
     * @param EntityInterface|Group $entity
     * @param \stdClass $object
     * @return EntityInterface
     */
    public function createEntity(EntityInterface $entity, \stdClass $object): EntityInterface
    {
        $entity->setExternalId($object->{self::FIELD_EXTERNAL_ID});
        $entity->setName($object->{self::FIELD_NAME});

        if (!isset($object->{self::FIELD_SPRINKLERS})) {
            return $entity;
        }

        /** @var SprinklerBuilder $sprinklerBuilder */
        $sprinklerBuilder = BuilderFactory::create($this->entityManager, SprinklerBuilder::class);
        foreach ($object->{self::FIELD_SPRINKLERS} as $sprinkler) {
            $entity->getSprinklers()->add($sprinklerBuilder->buildEntity($sprinkler));
        }

        return $entity;
    }

    /**
     * @param Group $object
     * @return array
     */
    protected function getSprinklersOutput($object): array
    {
        /** @var SprinklerBuilder $sprinklerBuilder */
        $sprinklerBuilder = BuilderFactory::create($this->entityManager, SprinklerBuilder::class);

        $output = [];

        foreach ($object->getSprinklers() as $sprinkler) {
            $output[] = $sprinklerBuilder->buildOutput($sprinkler);
        }

        return $output;
    }

    /**
     * @return string
     */
    protected function getClassname(): string
    {
        return Group::class;
    }
}