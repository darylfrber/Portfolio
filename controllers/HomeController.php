<?php

use RedBeanPHP\R as R;

class HomeController extends BaseController
{
    public function index(): void // Toon login pagina
    {
        $data = ['page' => 'home'];
        render('/home/index.twig', $data);
        exit();
    }

    public function contact(): void // Toon login pagina
    {
        $data = ['page' => 'contact'];
        render('/home/contact.twig', $data);
        exit();
    }

    public function projects(): void // Toon login pagina
    {
        $data = ['page' => 'projects'];
        render('/home/projects.twig', $data);
        exit();
    }
}

