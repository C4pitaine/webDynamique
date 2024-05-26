<?php
namespace App\Service;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class CheckedUser{

    /**
     * Empêche la connexion si l'utilisateur n'a pas confirmer son adresse E-mail
     *
     * @param CheckPassportEvent $event
     * @param UserRepository $repo
     * @return void
     */
    public function checkUser(CheckPassportEvent $event,UserRepository $repo)
    {
        $passport = $event->getPassport();
        $userPassport = $passport->getUser();
        $userPassport->getUserIdentifier();

        $user = $repo->findOneBy(['email'=>$userPassport],null);

        if(!$user){
            throw new CustomUserMessageAuthenticationException('Adresse email invalide.');
        }else{
            if (!$user->isChecked()) {
                throw new CustomUserMessageAuthenticationException('Veuillez confirmer votre email.');
            }
        }
    }
}   