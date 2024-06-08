<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\SearchType;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{

    /**
     * Permet d'afficher tous les articles côté utilisateur
     *
     * @param PaginationService $pagination
     * @param integer $page
     * @return Response
     */
    #[Route('/blog/{page<\d+>?1}/{recherche}', name: 'blog')]
    public function index(PaginationService $pagination,Request $request,int $page,string $recherche=""): Response
    {   
        $pagination->setEntityClass(Article::class)
                    ->setLimit(6)
                    ->setPage($page)
                    ->setSearch($recherche)
                    ->setTemplatePath("/partials/_pagination.html.twig");
        
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

        return $this->render('blog/index.html.twig', [
           'pagination' => $pagination,
           'formSearch' => $form->createView(),
        ]); 
    }
    
    /**
     * Permet d'afficher un article côté utilisateur
     *
     * @param Article $article
     * @return Response
     */
    #[Route('/blog/{slug}',name:'blog_show')]
    public function show(Article $article): Response
    {
        return $this->render('/blog/show.html.twig',[
            'article' => $article
        ]);
    }
}
