<?php

namespace App\Controller;

use App\Entity\Sujet;
use App\Form\SujetType;
use App\Form\SearchType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ForumController extends AbstractController
{
    /**
     * Permet d'ajouter un sujet
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/forum/create', name:"forum_create")]
    #[IsGranted('ROLE_USER')]
    public function create(EntityManagerInterface $manager,Request $request): Response
    {
        $sujet = new Sujet();

        $form = $this->createForm(SujetType::class,$sujet);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $sujet->setUser($this->getUser());
            $file = $form['image']->getData();
            if(!empty($file))
            {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename."-".uniqid().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory_forum'), 
                        $newFilename 
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }
                $sujet->setImage($newFilename);
            }else{
                $sujet->setImage("");
            }
            $manager->persist($sujet);
            $manager->flush();

            $this->addFlash('success','Le sujet : '.$sujet->getTitle().' a bien été ajouté');
            return $this->redirectToRoute('forum_index');
        }

        return $this->render('forum/new.html.twig',[
            'formSujet' => $form->createView(),
        ]);
    }

    /**
     * Permet de supprimer un sujet du forum
     *
     * @param EntityManagerInterface $manager
     * @param Sujet $sujet
     * @return Response
     */
    #[Route('/forum/{id}/delete', name:'forum_delete')]
    public function delete(EntityManagerInterface $manager,Sujet $sujet): Response
    {
        $this->addFlash("success","Le sujet ".$sujet->getTitle()." a bien été supprimé");

        if(!empty($sujet->getImage()))
        {
            unlink($this->getParameter('uploads_directory_forum').'/'.$sujet->getImage());
            $sujet->setImage('');
            $manager->persist($sujet);
        }

        $manager->remove($sujet);
        $manager->flush();
        return $this->redirectToRoute("forum_index");
    }

    /**
     * Permet d'afficher un sujet du forum
     *
     * @param Sujet $sujet
     * @return Response
     */
    #[Route('/forum/{slug}/show', name:'forum_show')]
    #[IsGranted('ROLE_USER')]
    public function show(Sujet $sujet): Response
    {
        return $this->render('forum/show.html.twig',[
            'sujet' => $sujet
        ]);
    }

     /**
     * Permet d'afficher de manière paginer les sujets + fonction de recherche
     *
     * @param Request $request
     * @param PaginationService $pagination
     * @param integer $page
     * @param string $recherche
     * @return Response
     */
    #[Route('/forum/{page<\d+>?1}/{recherche}', name: 'forum_index')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request,PaginationService $pagination,int $page,string $recherche =""): Response
    {
        $pagination->setEntityClass(Sujet::class)
                    ->setPage($page)
                    ->setLimit(10)
                    ->setOrder(['id'=>'DESC'])
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
        
        return $this->render('forum/index.html.twig', [
            'pagination' => $pagination,
            'formSearch' => $form->createView(),
            'search' => $recherche
        ]);
    }
}
