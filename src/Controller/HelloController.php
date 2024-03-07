<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HelloController extends AbstractController
{
    /**
     * @Route("/", name="hello")
     */
    public function hello(): Response
    {
        return new Response('<html><body>Hello zouzwka!</body></html>');
    }
}