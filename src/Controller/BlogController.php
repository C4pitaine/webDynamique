<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/blog/{page<\d+>?1}', name: 'blog')]
    public function index(PaginationService $pagination,int $page): Response
    {   
        $pagination->setEntityClass(Article::class)
                    ->setLimit(6)
                    ->setPage($page)
                    ->setSearch("");

        return $this->render('blog/index.html.twig', [
           'pagination' => $pagination,
        ]); 
    }
}
