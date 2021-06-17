<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->render(
            'index/index.html.twig', 
            [
                'controller_name' => 'IndexController',
            ]
        );
    }

    
    /**
     * @Route("/direBonjour", name="direBonjour")
     */
    public function direBonjour(): Response
    {
        return $this->render(
            'index/direBonjour.html.twig',
            [
                'name' => 'Nicolas',
                'surname' => 'M.'
            ]
        );
    }   
    
    /**
     * @Route("/bonjour", name="bonjour")
     */
    public function bonjour(): Response
    {
        return $this->render(
            'index/index.html.twig', 
            [
                'controller_name' => 'Bonjour',
            ]
        );
    }
}
