<?php

namespace App\Helper\Factory;

use App\Helper\Builder\BuilderInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class OutputFactory
 * @package App\Helper\Factory
 */
class BuilderFactory
{
    /**
     * @param EntityManager $entityManager
     * @param string $classname
     * @return BuilderInterface
     */
    public static function create(EntityManager $entityManager, string $classname): BuilderInterface
    {
        if (!class_exists($classname)) {
            throw new \InvalidArgumentException('Class ' . $classname . ' does not exist');
        }

        $class = new $classname($entityManager);

        if (!$class instanceof BuilderInterface) {
            throw new \InvalidArgumentException('Invalid classname ' . $classname);
        }

        return $class;
    }
}