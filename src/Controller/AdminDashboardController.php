<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard_index')]
    public function index(ContactRepository $contactRepo,UserRepository $userRepo): Response
    {
        $messageNotSeen = $contactRepo->findBy(['status'=>false]);
        $users = $userRepo->findAll();
        return $this->render('admin/dashboard/index.html.twig', [
            'messageNotSeen' => Count($messageNotSeen),
            'usersNumber' => Count($users)
        ]);
    }
}
