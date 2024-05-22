<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\PaginationService;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminArticleController extends AbstractController
{
    #[Route('/admin/article', name: 'admin_article_index')]
    public function index(ArticleRepository $repo,PaginationService $pagination,int $page): Response
    {
        $pagination->setEntityClass(Article::class)
                    ->setSearch("")
                    ->setPage($page)
                    ->setLimit(10);

        return $this->render('admin/article/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Permet d'ajouter un article
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/admin/article/create', name: 'admin_article_create')]
    public function create(Request $request,EntityManagerInterface $manager):Response
    {
        $article = new Article();
    }

    /**
     * Permet de supprimer un article
     *
     * @param Article $article
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/admin/article/{id}/delete',name: 'admin_article_delete')]
    public function delete(Article $article,EntityManagerInterface $manager): Response
    {
        $this->addFlash('danger','L\'article '.$article->getTitle().' a bien été supprimé');

        $manager->remove($article);
        $manager->flush();

        return $this->redirectToRoute('admin_article_index');
    }
}
