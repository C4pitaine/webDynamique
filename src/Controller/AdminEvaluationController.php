<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Entity\Evaluation;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEvaluationController extends AbstractController
{

    /**
     * Permet de supprimer une évaluation
     *
     * @param EntityManagerInterface $manager
     * @param Evaluation $eval
     * @return Response
     */
    #[Route('/admin/eval/{id}/delete', name:"admin_evaluation_delete")]
    public function delete(EntityManagerInterface $manager,Evaluation $eval):Response
    {
        $user = $eval->getUser();
        $this->addFlash('success',"L'évaluation de ".$user->getUsername()." a bien été supprimé");

        $manager->remove($eval);
        $manager->flush();

        return $this->redirectToRoute('admin_evaluation_index');
    }

    /**
     * Permet d'afficher toutes les évaluations paginer avec une recherche sur les utilisateurs
     *
     * @param PaginationService $pagination
     * @param Request $request
     * @param integer $page
     * @param string $recherche
     * @return Response
     */
    #[Route('/admin/evaluation/{page<\d+>?1}/{recherche}', name: 'admin_evaluation_index')]
    public function index(PaginationService $pagination,Request $request,int $page,string $recherche=""): Response
    {
        $pagination->setEntityClass(Evaluation::class)
                    ->setSearch($recherche)
                    ->setLimit(1)
                    ->setPage($page);

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($form->isSubmitted() && $form->isValid()){
                $recherche = $form->get('search')->getData();
                if($recherche !== null){
                    $pagination->setSearch($recherche)
                            ->setPage(1);
                }else{
                    $pagination->setSearch("")
                            ->setPage(1);
                }
            }
        }

        return $this->render('admin/evaluation/index.html.twig', [
            'pagination' => $pagination,
            'formSearch' => $form->createView(),
            'search' => $recherche
        ]);
    }
}
