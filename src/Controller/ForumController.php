<?php

namespace App\Controller;

use App\Entity\Sujet;
use App\Form\SearchType;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{
    #[Route('/forum/{page<\d+>?1}/{recherche}', name: 'forum_index')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request,PaginationService $pagination,int $page,string $recherche =""): Response
    {
        $pagination->setEntityClass(Sujet::class)
                    ->setPage($page)
                    ->setLimit(10)
                    ->setOrder(['id'=>'DESC'])
                    ->setSearch($recherche);

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
        
        return $this->render('forum/index.html.twig', [
            'pagination' => $pagination,
            'formSearch' => $form->createView(),
        ]);
    }
}
