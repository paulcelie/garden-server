<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\HueBridge;
use App\Helper\Factory\BuilderFactory;
use App\Helper\Builder\GroupBuilder;
use App\Repository\GroupRepository;
use Phue\Client;
use Phue\Command\GetLightById;
use Phue\Command\GetLights;
use Phue\Light;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GroupBuilder
 * @package App\Controller
 */
class GroupController extends AbstractController
{
    /**
     * @var GroupBuilder
     */
    protected $builder;

    /**
     * @Route("/group", name="group_list", methods={"GET"})
     */
    public function index()
    {
        /** @var GroupRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(Group::class);

        /** @var Group[] $groups */
        $groups = $repository->listGroupsWithSprinklers();

        /** @var Light[] $externalIds */
        $externalIds = [];
        /** @var HueBridge[] $bridges */
        $bridges = $this->getDoctrine()->getManager()->getRepository(HueBridge::class)->findAll();

        foreach ($bridges as $bridge) {
            $client = new Client($bridge->getIp(), $bridge->getUsername());
            $lights = $client->sendCommand(new GetLights());

            /** @var Light $light */
            foreach ($lights as $light) {
                $externalIds[$light->getId()] = $light;
            }
        }

        $output = [];
        foreach ($groups as $group) {

            $active = false;
            if (array_key_exists($group->getExternalId(), $externalIds)) {
                $active = $externalIds[$group->getExternalId()]->isOn();
            }

            $result = $this->getBuilder()->buildOutput($group);
            $result['active'] = $active;

            $output[] = $result;
        }

        return new JsonResponse($output, 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/group/{id}", name="group_get", methods={"GET"})
     *
     * @param int $id
     * @return object|JsonResponse
     */
    public function getOne(int $id)
    {
        /** @var Group $group */
        $group = $this->getDoctrine()->getRepository(Group::class)->find($id);

        /** @var HueBridge[] $bridges */
        $bridges = $this->getDoctrine()->getManager()->getRepository(HueBridge::class)->findAll();

        $output = $this->getBuilder()->buildOutput($group);

        foreach ($bridges as $bridge) {
            $client = new Client($bridge->getIp(), $bridge->getUsername());
            /** @var Light $light */
            $light = $client->sendCommand(new GetLightById($group->getExternalId()));

            if ($light) {
                $output['active'] = $light->isOn();
            }
        }

        return new JsonResponse($output, 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/group", name="group_add", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        $content = json_decode($request->getContent());

        $group = $this->getBuilder()->buildEntity($content, new Group());

        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();

        return new JsonResponse($this->getBuilder()->buildOutput($group), 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/group/{id}", name="group_update", methods={"PUT"})
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $group = $this->getDoctrine()->getRepository(Group::class)->find($id);

        $content = json_decode($request->getContent());

        $group = $this->getBuilder()->buildEntity($content, $group);

        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();

        return new JsonResponse($this->getBuilder()->buildOutput($group), 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }


    /**
     * @Route("/group/{id}", name="group_delete", methods={"DELETE"})
     *
     * @param int $id
     * @return object|JsonResponse
     */
    public function delete($id)
    {
        $group = $this->getDoctrine()->getRepository(Group::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($group);
        $em->flush();

        return new JsonResponse([], 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @return GroupBuilder
     */
    private function getBuilder(): GroupBuilder
    {
        if ($this->builder) {
            return $this->builder;
        }

        $this->builder = BuilderFactory::create($this->getDoctrine()->getManager(), GroupBuilder::class);

        return $this->builder;
    }
}