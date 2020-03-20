<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
* @IsGranted("ROLE_ADMIN")
*/
class AssistivetechnologyController extends AbstractController
{
    /**
     * @Route("/technologies-assistance", name="assistivetechnology_list")
     */
    public function assistivetechnology_list()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('assistivetechnology/list.html.twig', [
            'controller_name' => 'AssistivetechnologyController',
            'categories' => $categories,
        ]);
    }

}
