<?php

namespace App\Controller;

use App\Repository\EvaluationRepository;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(ServiceRepository $servicesRepo,EvaluationRepository $evalRepo): Response
    {
        return $this->render('home.html.twig', [
            'services' => $servicesRepo->findAll(),
            'evaluations' => $evalRepo->findBy([],null,3,null)
        ]);
    }
}
