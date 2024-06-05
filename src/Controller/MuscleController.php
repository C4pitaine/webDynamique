<?php

namespace App\Controller;

use App\Entity\Muscle;
use App\Form\MuscleModifyType;
use App\Form\MuscleType;
use App\Form\SearchType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class MuscleController extends AbstractController
{

    /**
     * Permet d'ajouter un muscle
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */ 
    #[Route('/admin/muscle/create', name:"admin_muscle_create")]
    public function create(EntityManagerInterface $manager,Request $request): Response
    {   
        $muscle = new Muscle();

        $form = $this->createForm(MuscleType::class,$muscle);
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
                        $this->getParameter('uploads_directory_muscles'), 
                        $newFilename 
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }
                $muscle->setImage($newFilename);
            }
            $manager->persist($muscle);
            $manager->flush();

            $this->addFlash('success','Le muscle '.$muscle->getName().' a bien été ajouté');
            return $this->redirectToRoute('admin_muscle_index');
        }

        return $this->render('admin/muscle/new.html.twig',[
            'formMuscle' => $form->createView(),
        ]);
    }

    /**
     * Permet de supprimer un muscle
     *
     * @param EntityManagerInterface $manager
     * @param Muscle $muscle
     * @return Response
     */
    #[Route('/admin/muscle/{id}/delete', name:"admin_muscle_delete")]
    public function delete(EntityManagerInterface $manager,Muscle $muscle) :Response
    {
        $this->addFlash('success','Le muscle '.$muscle->getName().' a bien été supprimé');

        if(!empty($muscle->getImage())){
            unlink($this->getParameter('uploads_directory_muscles').'/'.$muscle->getImage());
        }

        $manager->remove($muscle);
        $manager->flush($muscle);

        return $this->redirectToRoute('admin_muscle_index');
    }

    /**
     * Permet de modifier un muscle
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param Muscle $muscle
     * @return Response
     */
    #[Route('/admin/muscle/{id}/update', name:"admin_muscle_update")]
    public function update(EntityManagerInterface $manager,Request $request,Muscle $muscle): Response
    {   
        $muscleImage = $muscle->getImage();
        $muscle->setImage("");

        $form = $this->createForm(MuscleModifyType::class,$muscle);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $muscle->setImage($muscleImage);
            $file = $form['image']->getData();
            if(!empty($file))
            {
                if(!empty($muscleImage)){
                    unlink($this->getParameter('uploads_directory_muscles').'/'.$muscleImage);
                }
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename."-".uniqid().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory_muscles'), 
                        $newFilename 
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }
                $muscle->setImage($newFilename);
            }else{
                if(!empty($articleImage)){
                    $muscle->setImage($muscleImage);
                }
            }

            $manager->persist($muscle);
            $manager->flush();
            
            $this->addFlash('warning','Le muscle : '.$muscle->getName().' a bien été modifié');
            return $this->redirectToRoute('admin_muscle_index');
        }
        

        return $this->render('admin/muscle/update.html.twig',[
            'formMuscle' => $form->createView(),
            'muscleImage' => $muscleImage,
            'muscle' => $muscle,
        ]);
    }

    /**
     * Permet d'afficher les muscles de manière paginé + avec la recherche
     *
     * @param PaginationService $pagination
     * @param Request $request
     * @param integer $page
     * @param string $recherche
     * @return Response
     */
    #[Route('/admin/muscle/{page<\d+>?1}/{recherche}', name: 'admin_muscle_index')]
    public function index(PaginationService $pagination,Request $request,int $page,string $recherche=""): Response
    {
        $pagination->setEntityClass(Muscle::class)
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

        return $this->render('admin/muscle/index.html.twig', [
            'pagination' => $pagination,
            'formSearch' => $form->createView(),
        ]);
    }
}
