<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\SearchType;
use App\Form\ArticleType;
use App\Form\ArticleModifyType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminArticleController extends AbstractController
{
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

        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //gestion de l'image
            $file = $form['image']->getData();
            if(!empty($file))
            {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename."-".uniqid().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory_article'), 
                        $newFilename 
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }
                $article->setImage($newFilename);
            }
            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success','L\'article a bien été ajouté');
            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('/admin/article/new.html.twig',[
            'formArticle' => $form->createView(),
        ]);
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

        if(!empty($article->getImage()))
        {
            unlink($this->getParameter('uploads_directory_article').'/'.$article->getImage());
            $article->setImage('');
            $manager->persist($article);
        }

        $manager->remove($article);
        $manager->flush();

        return $this->redirectToRoute('admin_article_index');
    }

    /**
     * Permet d'afficher un article
     *
     * @param Article $article
     * @return Response
     */
    #[Route('/admin/article/{id}/show',name: 'admin_article_show')]
    public function show(Article $article): Response
    {
        return $this->render('admin/article/show.html.twig',[
            'article' => $article
        ]);
    }


    /**
     * Permet de modifier un article
     *
     * @param Article $article
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/admin/article/{id}/update',name: 'admin_article_update')]
    public function update(Article $article,Request $request,EntityManagerInterface $manager): Response
    {
        $articleImage = $article->getImage();
        $article->setImage("");
        $form = $this->createForm(ArticleModifyType::class,$article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $article->setImage($articleImage);
            $file = $form['image']->getData();
            if(!empty($file))
            {
                if(!empty($articleImage)){
                    unlink($this->getParameter('uploads_directory_article').'/'.$articleImage);
                }
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename."-".uniqid().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory_article'), 
                        $newFilename 
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }
                $article->setImage($newFilename);
            }else{
                if(!empty($articleImage)){
                    $article->setImage($articleImage);
                }
            }

            $manager->persist($article);
            $manager->flush();
            
            $this->addFlash('warning','L\'article : '.$article->getTitle().' a bien été modifié');
            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('admin/article/update.html.twig',[
            'article' => $article,
            'formArticle' => $form->createView(),
            'articleImage' => $articleImage,
        ]);
    }

    /**
     * Permet d'afficher les articles avec une recherche sur les titres de manière paginé
     *
     * @param PaginationService $pagination
     * @param integer $page
     * @return Response
     */
    #[Route('/admin/article/{page<\d+>?1}/{recherche}', name: 'admin_article_index')]
    public function index(Request $request,PaginationService $pagination,int $page,string $recherche=""): Response
    {
        $pagination->setEntityClass(Article::class)
                    ->setSearch($recherche)
                    ->setPage($page)
                    ->setLimit(10);

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

        return $this->render('admin/article/index.html.twig', [
            'pagination' => $pagination,
            'formSearch' => $form->createView(),
        ]);
    }
}
