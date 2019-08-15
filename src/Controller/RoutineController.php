<?php

namespace App\Controller;

use App\Entity\Routine;
use App\Entity\RoutineAction;
use App\Entity\RoutineCondition;
use App\Helper\Builder\RoutineActionBuilder;
use App\Helper\Builder\RoutineBuilder;
use App\Helper\Builder\RoutineConditionBuilder;
use App\Helper\Factory\BuilderFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RoutineController
 * @package App\Controller
 */
class RoutineController extends AbstractController
{
    /**
     * @Route("/routine", name="routine_get", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getAll()
    {
        $em = $this->getDoctrine()->getManager();
        $routines = $em->getRepository(Routine::class)->findAll();
        $routineBuilder = BuilderFactory::create($em, RoutineBuilder::class);

        $output = [];
        foreach ($routines as $routine) {
            $output[] = $routineBuilder->buildOutput($routine);
        }

        return new JsonResponse($output, 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/routine/{id}", name="routine_get_one", methods={"GET"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getOne(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $routine = $em->getRepository(Routine::class)->find($id);

        $builder = BuilderFactory::create($em, RoutineBuilder::class);

        return new JsonResponse($builder->buildOutput($routine), 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/routine", name="routine_add", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        return $this->edit($request);
    }

    /**
     * @Route("/routine/{id}", name="routine_update", methods={"PUT"})
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        return $this->edit($request, $id);
    }

    /**
     * @Route("/routine/{id}", name="routine_delete", methods={"DELETE"})
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $routine = $em->getRepository(Routine::class)->find($id);
        $em->remove($routine);
        $em->flush();

        return new JsonResponse([], 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }


    /**
     * @param Request $request
     * @param int|null $id
     * @return JsonResponse
     */
    protected function edit(Request $request, int $id = null)
    {
        $content = json_decode($request->getContent());

        $routine = new Routine();
        if ($id) {
            $routine = $this->getDoctrine()->getManager()->getRepository(Routine::class)->find($id);
        }

        $routineBuilder = BuilderFactory::create($this->getDoctrine()->getManager(), RoutineBuilder::class);

        /** @var Routine $routine */
        $routine = $routineBuilder->buildEntity($content, $routine);

        $em = $this->getDoctrine()->getManager();
        $em->persist($routine);
        $em->flush();

        $routineActionBuilder = BuilderFactory::create($em, RoutineActionBuilder::class);
        foreach ($content->{RoutineBuilder::FIELD_GROUPS} as $group) {
            $action = new RoutineAction();
            $group->{RoutineActionBuilder::FIELD_ROUTINE_ID} = $routine->getId();
            $action = $routineActionBuilder->buildEntity($group, $action);
            $em->persist($action);
            $routine->addRoutineAction($action);
        }

        $conditionActionBuilder = BuilderFactory::create($em, RoutineConditionBuilder::class);
        foreach ($content->{RoutineBuilder::FIELD_CONDITIONS} as $object) {
            $condition = new RoutineCondition();
            $object->{RoutineConditionBuilder::FIELD_ROUTINE_ID} = $routine->getId();
            $condition = $conditionActionBuilder->buildEntity($object, $condition);
            $em->persist($condition);
            $routine->addRoutineCondition($condition);
        }

        $em->persist($routine);
        $em->flush();

        return new JsonResponse($routineBuilder->buildOutput($routine), $id ? 201 : 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }
}