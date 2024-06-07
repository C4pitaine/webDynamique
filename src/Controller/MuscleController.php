<?php

namespace App\Controller;

use App\Entity\Muscle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MuscleController extends AbstractController
{
    #[Route('/muscle/{id}/show', name: 'muscle_show')]
    public function index(Muscle $muscle): Response
    {
        return $this->render('muscle/index.html.twig', [
            'muscle' => $muscle
        ]);
    }
}
