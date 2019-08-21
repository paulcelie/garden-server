<?php


namespace App\Command;

use App\Entity\Group;
use App\Entity\Routine;
use App\Entity\RoutineCondition;
use App\Entity\RoutineLog;
use App\Service\BuienRadar;
use App\Service\Hue;
use App\Service\Meteo;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class RoutineRunCommand
 * @package App\Command
 */
class RoutineRunCommand extends Command
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
     * @var BuienRadar
     */
    protected $buienRader;

    /**
     * @var Meteo
     */
    protected $meteo;

    /**
     * @var Hue
     */
    protected $hue;


    /**
     * RoutineRunCommand constructor.
     * @param string|null $name
     * @param ContainerInterface $container
     * @param BuienRadar $buienRadar
     * @param Meteo $meteo
     * @param Hue $hue
     */
    public function __construct(?string $name = null, ContainerInterface $container, BuienRadar $buienRadar, Meteo $meteo, Hue $hue)
    {
        parent::__construct($name);
        $this->container = $container;
        $this->buienRader = $buienRadar;
        $this->meteo = $meteo;
        $this->hue = $hue;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();

        /** @var RoutineLog|null $routineLog */
        $routineLog = $em->getRepository(RoutineLog::class)->getNext();
        if (!$routineLog) {
            return 0;
        }

        try {
            $this->validate($routineLog->getRoutine());
            $routineLog->setStartedAt(new \DateTime());
            $routineLog->setStatus(RoutineLog::STATUS_STARTED);

            $em->persist($routineLog);
            $em->flush();

            $status = $routineLog->getHandling() === RoutineLog::HANDLING_STOP ? Group::STATE_OFF : Group::STATE_ON;
            $this->hue->switchDevice($routineLog->getAction()->getGroupId(), $status);

            $routineLog->setMessage(sprintf('Switched group ' . $routineLog->getAction()->getGroupId()->getName() . ': ' . $status));
            $em->persist($routineLog);
            $em->flush();


        } catch (\Exception $exception) {
            $routineLog->setStatus(RoutineLog::STATUS_SKIPPED);
            $routineLog->setMessage($exception->getMessage());
        }

        $em->persist($routineLog);
        $em->flush();

    }

    /**
     * @param RoutineLog $routineLog
     * @throws \Exception
     */
    protected function validate(RoutineLog $routineLog)
    {
        if ($routineLog->getHandling() === RoutineLog::HANDLING_STOP) {
            return;
        }

        $routine = $routineLog->getRoutine();

        $conditions = $routine->getRoutineConditions();
        if (empty($conditions)) {
            return;
        }

        $results = [];
        foreach ($conditions as $condition) {
            if ($condition->getType() === RoutineCondition::TYPE_BUIENRADAR) {
                $results[RoutineCondition::TYPE_BUIENRADAR] = $this->doesConditionMatchBuienRadar($condition);
                continue;
            }

            if ($condition->getType() === RoutineCondition::TYPE_METEO) {
                $results[RoutineCondition::TYPE_METEO] = $this->doesConditionMatchMeteo($condition);
                continue;
            }
        }

        if ($routine->getConditionType() === Routine::CONDITION_TYPE_AND) {
            foreach ($results as $type => $result) {
                if ($result) {
                    continue;
                }

                throw new \Exception(sprintf('Conditie voldoet niet aan de verwachtingen van %s',  RoutineCondition::TYPE_BUIENRADAR ? 'Buienradar' : 'Meteo'));
            }

            return;
        }

        if (!in_array(true, $results)) {
            foreach ($results as $type => $result) {
                if ($result) {
                    continue;
                }

                throw new \Exception(sprintf('Conditie voldoet niet aan de verwachtingen van %s',  RoutineCondition::TYPE_BUIENRADAR ? 'Buienradar' : 'Meteo'));
            }
        }
    }

    /**
     * @param RoutineCondition $condition
     * @return bool
     * @throws \Exception
     */
    protected function doesConditionMatchBuienRadar(RoutineCondition $condition)
    {
        $rainInPast24Hours = $this->buienRader->getRainInPast24Hours();

        return $rainInPast24Hours < $condition->getMilimeter();
    }

    /**
     * @param RoutineCondition $condition
     * @return bool
     * @throws \Exception
     */
    protected function doesConditionMatchMeteo(RoutineCondition $condition)
    {
        $expedtedRainInRestOfTheDay = $this->meteo->getExpectedRainInMm();

        return $expedtedRainInRestOfTheDay < $condition->getMilimeter();
    }
}