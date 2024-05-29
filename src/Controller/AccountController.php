<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Form\EvaluationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher le profil d'un utilisateur
     *
     * @return Response
     */
    #[Route('/profil',name:'account_profil')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        $user = $this->getUser();
       return $this->render('account/index.html.twig',[
            'user' =>$user
       ]);
    }

    /**
     * Permet d'ajouter une evaluation
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/profil/eval', name:'account_profil_eval')]
    #[IsGranted('ROLE_MEMBER')]
    public function eval(EntityManagerInterface $manager,Request $request): Response
    {
        $user = $this->getUser();
        $eval = new Evaluation();
        $form = $this->createForm(EvaluationType::class,$eval);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $eval->setUser($user);
            $manager->persist($eval);
            $manager->flush();

            $this->addFlash('success','Votre évaluation a été envoyée avec succès');
            return $this->redirectToRoute('account_profil');
        }

        return $this->render('account/eval.html.twig',[
            'formEval' => $form->createView(),
        ]);
    }

}
