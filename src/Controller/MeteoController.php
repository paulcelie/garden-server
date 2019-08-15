<?php


namespace App\Controller;

use App\Entity\Meteo;
use App\Helper\Builder\MeteoBuilder;
use App\Helper\Factory\BuilderFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MeteoController
 * @package App\Controller
 */
class MeteoController extends AbstractController
{
    /**
     * @Route("/meteo", name="meteo_get", methods={"GET"})
     *
     * @return object|JsonResponse
     */
    public function getAll()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $meteos = $entityManager->getRepository(Meteo::class)->findAll();

        $builder = BuilderFactory::create($entityManager, MeteoBuilder::class);
        $output = [];
        foreach ($meteos as $meteo) {
            $output[] = $builder->buildOutput($meteo);
        }

        return new JsonResponse($output, 200, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }

    /**
     * @Route("/meteo", name="meteo_add", methods={"POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        return $this->edit($request);
    }

    /**
     * @Route("/meteo/{id}", name="meteo_update", methods={"PUT"})
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
     * @param Request $request
     * @param int|null $id
     * @return JsonResponse
     */
    protected function edit(Request $request, int $id = null)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $meteo = new Meteo();
        if ($id) {
            $meteo = $entityManager->getRepository(Meteo::class)->find($id);
        }

        $content = json_decode($request->getContent());

        $builder = BuilderFactory::create($entityManager, MeteoBuilder::class);
        $meteo = $builder->buildEntity($content, $meteo);

        $entityManager->persist($meteo);
        $entityManager->flush();

        return new JsonResponse($builder->buildOutput($meteo), $id ? 200 : 201, [
            'Access-Control-Allow-Origin' => '*'
        ]);
    }
}