<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
     /**
     * Permet d'afficher tous les articles 
     *
     * @param PaginationService $pagination
     * @param integer $page
     * @return Response
     */
    #[Route('/blog/{page<\d+>?1}', name: 'blog')]
    public function index(PaginationService $pagination,int $page): Response
    {   
        $pagination->setEntityClass(Article::class)
                    ->setLimit(6)
                    ->setPage($page)
                    ->setSearch("")
                    ->setTemplatePath("/partials/_pagination.html.twig");

        return $this->render('blog/index.html.twig', [
           'pagination' => $pagination,
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
