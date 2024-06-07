<?php

namespace App\Controller;

use App\Entity\Entrainement;
use App\Form\SearchType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EntrainementController extends AbstractController
{
    #[Route('/entrainement/{page<\d+>?1}/{recherche}', name: 'entrainement_index')]
    #[IsGranted('ROLE_USER')]
    public function index(PaginationService $pagination,EntityManagerInterface $manager,Request $request,int $page,string $recherche=""): Response
    {
        $pagination->setEntityClass(Entrainement::class)
                    ->setPage($page)
                    ->setLimit(3)
                    ->setSearch($recherche)
                    ->setTemplatePath('partials/_pagination.html.twig');

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

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

        return $this->render('entrainement/index.html.twig', [
            'pagination' => $pagination,
            'formSearch' => $form->createView(),
            'search' => $recherche
        ]);
    }
}
