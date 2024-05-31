<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SeanceController extends AbstractController
{
    #[Route('/seance/create', name: 'seance_create')]
    public function create(EntityManagerInterface $manager, Request $request): Response
    {
        return $this->render('seance/index.html.twig', [
            
        ]);
    }
}
