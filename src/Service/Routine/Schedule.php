<?php


namespace App\Service\Routine;

use App\Entity\Routine;
use App\Entity\RoutineLog;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class Schedule
 * @package App\Service\Routine
 */
class Schedule
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Schedule constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function scheduleRoutines()
    {
        $this->clearFutureSchedules();

        $routines = $this->entityManager->getRepository(Routine::class)->findAll();

        $now = new \DateTime();
        $weekOfDay = $now->format('w');

        /** @var Routine $routine */
        foreach ($routines as $routine) {
            $days = $routine->getDays();
            if (!$days) {
                continue;
            }

            $days = explode(",", $days);
            if (!in_array($weekOfDay, $days)) {
                continue;
            }

            $start = $routine->getStartTime();

            foreach ($routine->getRoutineActions() as $action) {

                if ($start < $now) {
                    continue;
                }

                $log = new RoutineLog();
                $log->setRoutine($routine);
                $log->setAction($action);
                $log->setStatus(RoutineLog::STATUS_PENDING);
                $log->setHandling(RoutineLog::HANDLING_START);
                $log->setScheduledAt($start);
                $this->entityManager->persist($log);

                $start->modify('+' . $action->getDuration() . ' minutes');

                $log = new RoutineLog();
                $log->setRoutine($routine);
                $log->setAction($action);
                $log->setStatus(RoutineLog::STATUS_PENDING);
                $log->setHandling(RoutineLog::HANDLING_STOP);
                $log->setScheduledAt($start);
                $this->entityManager->persist($log);

                $start->modify('+' . $action->getDuration() . ' minutes');
            }
        }

        $this->entityManager->flush();
    }

    protected function clearFutureSchedules()
    {
        $futures = $this->entityManager->getRepository(RoutineLog::class)->getFutures();
        foreach ($futures as $future) {
            $this->entityManager->remove($future);
        }

        $this->entityManager->flush();
    }
}