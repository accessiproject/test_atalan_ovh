<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestdatatableController extends AbstractController
{
    /**
     * @Route("/testdatatable", name="testdatatable")
     */
    public function index()
    {
        return $this->render('testdatatable/index.html.twig', [
            'controller_name' => 'TestdatatableController',
        ]);
    }
}
