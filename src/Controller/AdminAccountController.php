<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\TooManyLoginAttemptsAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * Fonction de connexion
     *
     * @param AuthenticationUtils $utils
     * @return Response
     */
    #[Route('/admin/login', name: 'admin_account_login')]
    public function index(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        $loginError = null;

        if($error instanceof TooManyLoginAttemptsAuthenticationException)
        {
            $loginError = "Tentatives de connexion dépassées, veuillez réessayez plus tard";
        }

        $user = $this->getUser();
        if($user)
        {
            if(in_array('ROLE_ADMIN',$user->getRoles())){
                return $this->redirectToRoute('admin_dashboard_index');
            }
        }

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !==null,
            'username' => $username,
            'loginError' => $loginError
        ]);
    }

    /**
     * Foncton de déconnexion
     *
     * @return void
     */
    #[Route('/admin/logout',name: 'admin_account_logout')]
    public function logout():void
    {
        
    }
}
