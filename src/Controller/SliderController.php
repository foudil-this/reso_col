<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SliderController
 * @package App\Controller
 *
 */
class SliderController extends AbstractController
{
    /**
     * @Route("/slider", name="slider")
     */
    public function index()
    {
        return $this->render('slider/index.html.twig', [
            'controller_name' => 'SliderController',
        ]);
    }
}
