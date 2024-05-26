<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\TooManyLoginAttemptsAuthenticationException;

class UserController extends AbstractController
{
    /**
     * Permet à l'utilisateur de se connecter
     *
     * @param AuthenticationUtils $utils
     * @return Response
     */
    #[Route('/login', name: 'account_login')]
    public function index(AuthenticationUtils $utils,UserRepository $repo): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        $loginError = null;
        $emailError = null;

        if($error instanceof TooManyLoginAttemptsAuthenticationException){
            $loginError = "Trop de tentatives de connexion. Veuillez attendre 15minutes";
        }
        if($error instanceof CustomUserMessageAuthenticationException){
            $emailError = "Veuillez confirmer votre email";
        }

        if(!$error && $username){
            if($user = $repo->findOneBy(['email'=>$username],null)){
                if(!$user->isChecked()){
                    $loginError = "Veuillez confirmer votre email";
                }
            }
        }

        return $this->render('user/index.html.twig', [
            'error' => $error !==null,
            'username' => $username,
            'loginError' => $loginError,
            'emailError' => $emailError
        ]);
    }

    /**
     * Permet à l'utilisateur de se déconnecter
     *
     * @return void
     */
    #[Route('/logout',name:'account_logout')]
    public function logout(): void 
    {

    }
}
