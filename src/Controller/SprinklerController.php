<?php

namespace App\Controller;

use App\Entity\Sprinkler;
use App\Helper\Factory\BuilderFactory;
use App\Helper\Builder\SprinklerBuilder;
use App\Repository\SprinklerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SprinklerBuilder
 * @package App\Controller
 */
class SprinklerController extends AbstractController
{
    /**
     * @var SprinklerBuilder
     */
    protected $builder;

    /**
     * @Route("/sprinkler", name="sprinkler_list", methods={"GET"})
     */
    public function index()
    {
        /** @var SprinklerRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(Sprinkler::class);

        $sprinklers = $repository->findAll();

        $output = [];
        foreach ($sprinklers as $sprinkler) {
            $output[] = $this->getBuilder()->buildOutput($sprinkler);
        }

        return new JsonResponse($output, 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/sprinkler/{id}", name="sprinkler_get", methods={"GET"})
     *
     * @param int $id
     * @return object|JsonResponse
     */
    public function getOne(int $id)
    {
        $sprinkler = $this->getDoctrine()->getRepository(Sprinkler::class)->find($id);

        return new JsonResponse($this->getBuilder()->buildOutput($sprinkler));
    }

    /**
     * @Route("/sprinkler", name="sprinkler_add", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        $content = json_decode($request->getContent());

        $sprinkler = $this->getBuilder()->buildEntity($content, new Sprinkler());

        $em = $this->getDoctrine()->getManager();
        $em->persist($sprinkler);
        $em->flush();

        return new JsonResponse($this->getBuilder()->buildOutput($sprinkler), 201, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/sprinkler/{id}", name="sprinkler_update", methods={"PUT"})
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $sprinkler = $this->getDoctrine()->getRepository(Sprinkler::class)->find($id);

        $content = json_decode($request->getContent());

        $sprinkler = $this->getBuilder()->buildEntity($content, $sprinkler);

        $em = $this->getDoctrine()->getManager();
        $em->persist($sprinkler);
        $em->flush();

        return new JsonResponse($this->getBuilder()->buildOutput($sprinkler));
    }


    /**
     * @Route("/sprinkler/{id}", name="sprinkler_delete", methods={"DELETE"})
     *
     * @param int $id
     * @return object|JsonResponse
     */
    public function delete(int $id)
    {
        $sprinkler = $this->getDoctrine()->getRepository(Sprinkler::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($sprinkler);
        $em->flush();

        return new JsonResponse([]);
    }

    /**
     * @return SprinklerBuilder
     */
    private function getBuilder(): SprinklerBuilder
    {
        if ($this->builder) {
            return $this->builder;
        }

        $this->builder = BuilderFactory::create($this->getDoctrine()->getManager(), SprinklerBuilder::class);

        return $this->builder;
    }
}