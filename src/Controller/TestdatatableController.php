<?php

namespace App\Controller;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestdatatableController extends AbstractController
{
    
    /**
     * @Route("/test", name="test_datatable")
     */
    public function index()
    {   
        return $this->render('testdatatable/list.html.twig', [
            'controller_name' => 'SurveyController',
        ]);
    }
}