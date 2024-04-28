<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminServicesController extends AbstractController
{
    #[Route('/admin/services', name: 'admin_services_index')]
    public function index(): Response
    {
        return $this->render('admin/services/index.html.twig', [
            'controller_name' => 'AdminServicesController',
        ]);
    }
}
