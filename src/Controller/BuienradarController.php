<?php

namespace App\Controller;

use App\Entity\BuienRadar;
use App\Helper\Builder\BuienRadarBuilder;
use App\Helper\Factory\BuilderFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class BuienradarController
 * @package App\Controller
 */
class BuienradarController extends AbstractController
{
    /**
     * @Route("/buienradar", name="buienradar_get", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getAll()
    {
        $em = $this->getDoctrine()->getManager();
        $buienRadars = $em->getRepository(BuienRadar::class)->findAll();
        $output = [];

        $builder = BuilderFactory::create($em, BuienRadarBuilder::class);
        foreach ($buienRadars as $buienRadar) {
            $output[] = $builder->buildOutput($buienRadar);
        }

        return new JsonResponse($output, 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/buienradar", name="buienradar_add", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        return $this->save($request);
    }

    /**
     * @Route("/buienradar/{id}", name="buienradar_update", methods={"PUT"})
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function edit(Request $request, int $id)
    {
        return $this->save($request, $id);
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return JsonResponse
     */
    protected function save(Request $request, int $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        $buienRadar = new BuienRadar();
        if ($id) {
            $buienRadar = $em->getRepository(BuienRadar::class)->find($id);
        }

        $builder = BuilderFactory::create($em, BuienRadarBuilder::class);
        $content = json_decode($request->getContent());

        $buienRadar = $builder->buildEntity($content, $buienRadar);
        $em->persist($buienRadar);
        $em->flush();

        return new JsonResponse($builder->buildOutput($buienRadar), $id ? 200 : 201, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }
}