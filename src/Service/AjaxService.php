<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class AjaxService
{
    //private $object;
    
    /*
    public function __construct(Object $object) 
    {
        $this->object = $object;
    }
    */

    public function test_ajax($object)
    {
       $responseArray = array();
       foreach($object as $value)
       {
           $responseArray[] = array(
               "email" => $value->getEmail(),
            );
        }
        //return new JsonResponse($responseArray);
        return $responseArray;
    }
}
