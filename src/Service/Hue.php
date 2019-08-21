<?php


namespace App\Service;

use App\Entity\Group;
use App\Entity\HueBridge;
use Phue\Client;
use Phue\Command\GetLightById;
use Phue\Light;

/**
 * Class Hue
 * @package App\Service
 */
class Hue extends AbstractEntityManagerService
{
    /**
     * @param Group $group
     * @param string $state
     * @throws \Exception
     */
    public function switchDevice(Group $group, string $state = Group::STATE_ON)
    {
        /** @var HueBridge $bridge */
        $bridge = $this->getEntityClass();

        $client = new Client($bridge->getIp(), $bridge->getUsername());

        /** @var Light $light */
        $light = $client->sendCommand(new GetLightById($group->getExternalId()));

        if (!$light) {
            throw new \Exception('Device with id ' . $group->getExternalId() . ' not found');
        }

        $status = $state === Group::STATE_ON ? true : false;

        $light->setOn($status);
    }

    /**
     * @return string
     */
    protected function getEntityClass(): string
    {
        return HueBridge::class;
    }
}