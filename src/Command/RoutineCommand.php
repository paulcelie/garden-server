<?php

namespace App\Command;

use App\Entity\Routine;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RoutineCommand
 * @package App\Command
 */
class RoutineCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'routine:run';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * RoutineCommand constructor.
     * @param string|null $name
     * @param ContainerInterface $container
     */
    public function __construct(?string $name = null, ContainerInterface $container)
    {
        parent::__construct($name);
        $this->container = $container;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();
        $routines = $em->getRepository(Routine::class)->findAll();

        echo count($routines);
    }
}