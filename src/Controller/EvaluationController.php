<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Form\EvaluationType;
use App\Repository\UserRepository;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EvaluationController extends AbstractController
{
    /**
     * Permet à l'utilisateur d'ajouter une evaluation
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/evaluation/create', name:'evaluation_create')]
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
            return $this->redirectToRoute('evaluations');
        }

        return $this->render('evaluation/new.html.twig',[
            'formEval' => $form->createView(),
        ]);
    }

    /**
     * Permet à l'utilisateur de supprimer une de ses évaluations
     *
     * @param EntityManagerInterface $manager
     * @param Evaluation $eval
     * @return Response
     */
    #[Route('/evaluation/{id}/delete', name:"evaluation_delete")]
    public function delete(EntityManagerInterface $manager,Evaluation $eval):Response
    {
        $this->addFlash('success','Votre évaluation a bien été supprimée');

        $manager->remove($eval);
        $manager->flush();

        return $this->redirectToRoute('evaluations');
    }

    /**
     * Récupération des évaluations d'un utilisateur
     *
     * @param EvaluationRepository $evalRepo
     * @param UserRepository $userRepo
     * @return Response
     */
    #[Route('/evaluations', name:'evaluations')]
    #[IsGranted('ROLE_MEMBER')]
    public function showEvals(EvaluationRepository $evalRepo,UserRepository $userRepo):Response
    {
        $user = $this->getUser();
        $userEval = $userRepo->findBy(['email'=>$user->getUserIdentifier()]);
        $evals = $evalRepo->findBy(['user'=>$userEval[0]->getId()]);

        return $this->render('evaluation/index.html.twig',[
            'user' => $user,
            'evaluations' => $evals
        ]);
    }
}
