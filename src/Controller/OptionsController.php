<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OptionsController
 * @package App\Controller
 */
class OptionsController extends AbstractController
{
    /**
     * @Route("/{req}", name="options", methods={"OPTIONS"}, requirements={"req": ".+"})
     */
    public function index($req)
    {
        return new Response('', 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-control-allow-methods' => 'GET,POST,PUT,DELETE',
            'Access-control-allow-headers' => 'Content-Type'
        ]);
    }
}