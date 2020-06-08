<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class DeleteService
{
    
    public function delete($object, String $route, $id, EntityManagerInterface $manager)
    {
        $manager->remove($object);
        $manager->flush();
        return $this->redirectToRoute($route, [
            'id' => $object->getId(),
        ]);
    }
}