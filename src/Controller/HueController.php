<?php


namespace App\Controller;

use App\Entity\Group;
use App\Entity\HueBridge;
use App\Helper\Builder\GroupBuilder;
use App\Helper\Builder\HueBridgeBuilder;
use App\Helper\Factory\BuilderFactory;
use Phue\Client;
use Phue\Command\CreateUser;
use Phue\Command\GetLightById;
use Phue\Command\GetLights;
use Phue\Light;
use Phue\Transport\Exception\LinkButtonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class HueController
 * @package App\Controller
 */
class HueController extends AbstractController
{
    /**
     * @Route("/hue/devices", name="hue_devices_get", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function listDevices()
    {
        $devices = [];

        /** @var HueBridge[] $bridges */
        $bridges = $this->getDoctrine()->getManager()->getRepository(HueBridge::class)->findAll();

        $externalIds = [];
        $groups = $this->getDoctrine()->getManager()->getRepository(Group::class)->findAll();

        /** @var Group $group */
        foreach ($groups as $group) {
            $externalIds[$group->getExternalId()] = $group;
        }

        $groupFactory = BuilderFactory::create($this->getDoctrine()->getManager(), GroupBuilder::class);

        foreach ($bridges as $bridge) {
            $client = new Client($bridge->getIp(), $bridge->getUsername());
            $lights = $client->sendCommand(new GetLights());

            /** @var Light $light */
            foreach ($lights as $light) {

                $device = [
                    'external_id' => $light->getId(),
                    'name' => $light->getName(),
                ];

                if (array_key_exists($light->getId(), $externalIds)) {
                    $device['group'] = $groupFactory->buildOutput($externalIds[$light->getId()]);
                }

                $devices[] = $device;
            }
        }

        return new JsonResponse($devices, 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/hue/device/state/{id}", name="Hue_device_state", methods={"GET"})
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getDeviceState(string $id)
    {
        /** @var HueBridge[] $bridges */
        $bridges = $this->getDoctrine()->getManager()->getRepository(HueBridge::class)->findAll();
        foreach ($bridges as $bridge) {
            $client = new Client($bridge->getIp(), $bridge->getUsername());
            /** @var Light $light */
            $light = $client->sendCommand(new GetLightById($id));

            if ($light) {
                return new JsonResponse([
                    'id' => $light->getId(),
                    'state' => $light->isOn()
                ], 200, [
                    'Access-Control-Allow-Origin' => '*'
                ]);
            }
        }
    }

    /**
     * @Route("/hue/devices/{id}/{state}", name="hue_device_state", methods={"PUT"})
     *
     * @param string $id
     * @param string $state
     * @return JsonResponse
     */
    public function switchDeviceState(string $id, string $state = Group::STATE_ON)
    {
        /** @var HueBridge[] $bridges */
        $bridges = $this->getDoctrine()->getManager()->getRepository(HueBridge::class)->findAll();

        foreach ($bridges as $bridge) {
            $client = new Client($bridge->getIp(), $bridge->getUsername());

            /** @var Light $light */
            $light = $client->sendCommand(new GetLightById($id));

            if (!$light) {
                continue;
            }

            $status = $state === Group::STATE_ON ? true : false;

            $light->setOn($status);
        }

        return new JsonResponse([], 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/hue/bridge", name="hue_bridge_add", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function registerBridge(Request $request)
    {
        $content = json_decode($request->getContent());

        $hueBridge = new HueBridge();
        $builder = BuilderFactory::create($this->getDoctrine()->getManager(), HueBridgeBuilder::class);

        $builder->buildEntity($content, $hueBridge);

        $client = new Client($hueBridge->getIp(), $hueBridge->getUsername());

        try {
            $response = $client->sendCommand(new CreateUser());
        } catch (LinkButtonException $exception) {
            return new JsonResponse($builder->buildOutput($hueBridge), 200, [
                'Access-Control-Allow-Origin' => '*'
            ]);
        }

        $hueBridge->setUsername($response->username);

        $em = $this->getDoctrine()->getManager();
        $em->persist($hueBridge);
        $em->flush();


        return new JsonResponse($builder->buildOutput($hueBridge), 201, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/hue/bridge", name="hue_bridge_get", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function listBridges()
    {
        $bridges = $this->getDoctrine()->getManager()->getRepository(HueBridge::class)->findAll();
        $output = [];

        $builder = BuilderFactory::create($this->getDoctrine()->getManager(), HueBridgeBuilder::class);
        foreach ($bridges as $bridge) {
            $output[] = $builder->buildOutput($bridge);
        }

        return new JsonResponse($output, 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/hue/bridge/{id}", name="hue_bridge_delete", methods={"DELETE"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function unregisterBridge(int $id)
    {
        $bridge = $this->getDoctrine()->getManager()->getRepository(HueBridge::class)->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($bridge);
        $em->flush();

        return new JsonResponse([], 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

}