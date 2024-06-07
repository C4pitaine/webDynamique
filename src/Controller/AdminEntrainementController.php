<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Entity\Entrainement;
use App\Form\EntrainementType;
use App\Service\PaginationService;
use App\Form\EntrainementModifyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminEntrainementController extends AbstractController
{
    /**
     * Permet d'ajouter un entrainement
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */ 
    #[Route('/admin/entrainement/create', name:"admin_entrainement_create")]
    public function create(EntityManagerInterface $manager,Request $request): Response
    {   
        $entrainement = new Entrainement();

        $form = $this->createForm(EntrainementType::class,$entrainement);
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
                        $this->getParameter('uploads_directory_entrainements'), 
                        $newFilename 
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }
                $entrainement->setImage($newFilename);
            }
            $manager->persist($entrainement);
            $manager->flush();

            $this->addFlash('success','L\'entrainement '.$entrainement->getTitle().' a bien été ajouté');
            return $this->redirectToRoute('admin_entrainement_index');
        }

        return $this->render('admin/entrainement/new.html.twig',[
            'formEntrainement' => $form->createView(),
        ]);
    }

    /**
     * Permet de supprimer un entrainement
     *
     * @param EntityManagerInterface $manager
     * @param Entrainement $entrainement
     * @return Response
     */
    #[Route('/admin/entrainement/{id}/delete', name:"admin_entrainement_delete")]
    public function delete(EntityManagerInterface $manager,Entrainement $entrainement) :Response
    {
        $this->addFlash('success','L\'entrainement '.$entrainement->getTitle().' a bien été supprimé');

        if(!empty($entrainement->getImage())){
            unlink($this->getParameter('uploads_directory_entrainements').'/'.$entrainement->getImage());
        }

        $manager->remove($entrainement);
        $manager->flush();

        return $this->redirectToRoute('admin_entrainement_index');
    }

    /**
     * Permet de modifier un muscle
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param Entrainement $entrainement
     * @return Response
     */
    #[Route('/admin/entrainement/{id}/update', name:"admin_entrainement_update")]
    public function update(EntityManagerInterface $manager,Request $request,Entrainement $entrainement): Response
    {   
        $entrainementImage = $entrainement->getImage();
        $entrainement->setImage("");

        $form = $this->createForm(EntrainementModifyType::class,$entrainement);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entrainement->setImage($entrainementImage);
            $file = $form['image']->getData();
            if(!empty($file))
            {
                if(!empty($entrainementImage)){
                    unlink($this->getParameter('uploads_directory_entrainements').'/'.$entrainementImage);
                }
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename."-".uniqid().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory_entrainements'), 
                        $newFilename 
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }
                $entrainement->setImage($newFilename);
            }else{
                if(!empty($entrainementImage)){
                    $entrainement->setImage($entrainementImage);
                }
            }

            $manager->persist($entrainement);
            $manager->flush();
            
            $this->addFlash('warning','L\'entrainement : '.$entrainement->getTitle().' a bien été modifié');
            return $this->redirectToRoute('admin_entrainement_index');
        }
        

        return $this->render('admin/entrainement/update.html.twig',[
            'formEntrainement' => $form->createView(),
            'entrainementImage' => $entrainementImage,
            'entrainement' => $entrainement,
        ]);
    }

    /**
     * Permet d'afficher les muscles de manière paginé + la recherche
     *
     * @param PaginationService $pagination
     * @param Request $request
     * @param integer $page
     * @param string $recherche
     * @return Response
     */
    #[Route('/admin/entrainement/{page<\d+>?1}/{recherche}', name: 'admin_entrainement_index')]
    public function index(PaginationService $pagination,Request $request,int $page,string $recherche=""): Response
    {
        $pagination->setEntityClass(Entrainement::class)
                    ->setPage($page)
                    ->setLimit(10)
                    ->setSearch($recherche);

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

        return $this->render('admin/entrainement/index.html.twig', [
            'pagination' => $pagination,
            'formSearch' => $form->createView(),
        ]);
    }
}
