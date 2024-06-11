<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\UserUpdateType;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use App\Service\PaginationService;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher le profil d'un utilisateur
     *
     * @return Response
     */
    #[Route('/profil/{page<\d+>?1}',name:'account_profil')]
    #[IsGranted('ROLE_USER')]
    public function profile(int $page,PaginationService $pagination): Response
    {
        $user = $this->getUser();
        $pagination->setEntityClass(Seance::class)
                    ->setLimit(10)
                    ->setPage($page)
                    ->setTemplatePath('partials/_paginationWithoutSearch.html.twig')
                    ->setSearch($user->getEmail());
                    
       return $this->render('account/index.html.twig',[
            'user' =>$user,
            'pagination' => $pagination
       ]);
    }

    /**
     * Permet à l'utilisateur de modifier son mot de passe
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route("/profile/passwordUpdate", name:"account_profile_passwordUpdate")]
    #[IsGranted("ROLE_USER")]
    public function updatePassword(EntityManagerInterface $manager,Request $request,UserPasswordHasherInterface $hasher): Response
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class,$passwordUpdate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!password_verify($passwordUpdate->getOldPassword(),$user->getPassword()))
            {
                $form->get('oldPassword')->addError(new FormError('Erreur dans le mot de passe actuel'));
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $hasher->hashPassword($user,$newPassword);

                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success','Votre mot de passe a bien été modifié');
                return $this->redirectToRoute('account_profil');
            }
        }

        return $this->render('account/passwordUpdate.html.twig',[
            'formPasswordUpdate' => $form->createView(),
        ]);
    }

    /**
     * Permet à l'utilisateur de modifier son nom d'utilisateur
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/profile/userUpdate', name:"account_profile_userUpdate")]
    #[IsGranted('ROLE_USER')]
    public function updateUser(EntityManagerInterface $manager,Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserUpdateType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success',"Votre nom d'utilisateur est maintenant : ".$user->getUsername());
            return $this->redirectToRoute('account_profil');
        }

        return $this->render('account/userUpdate.html.twig',[
            'formUserUpdate' => $form->createView(),
        ]);
    }
}
