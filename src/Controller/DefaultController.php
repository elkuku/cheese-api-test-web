<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(SerializerInterface $serializer)
    {
        return $this->render('default/index.html.twig', [
            'user' => $serializer->serialize($this->getUser(), 'jsonld'),
        ]);
    }
}
