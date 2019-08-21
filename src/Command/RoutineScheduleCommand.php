<?php

namespace App\Command;

use App\Service\Routine\Schedule;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RoutineCommand
 * @package App\Command
 */
class RoutineScheduleCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'routine:schedule';

    /**
     * @var Schedule
     */
    protected $schedule;

    /**
     * RoutineScheduleCommand constructor.
     * @param string|null $name
     * @param Schedule $schedule
     */
    public function __construct(?string $name = null, Schedule $schedule)
    {
        parent::__construct($name);
        $this->schedule = $schedule;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->schedule->scheduleRoutines();
    }
}