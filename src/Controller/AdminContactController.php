<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\SearchType;
use App\Service\PaginationService;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminContactController extends AbstractController
{
     /**
     * Permet d'afficher un message et de passer son status en vu
     *
     * @param Contact $contact
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/admin/contact/{id}/show',name: 'admin_contact_show')]
    public function show(Contact $contact, EntityManagerInterface $manager): Response
    {
        $contact->setStatus(true);
        $manager->persist($contact);
        $manager->flush();

        return $this->render('admin/contact/show.html.twig',[
            'contact'=>$contact
        ]);
    }

    /**
     * Permet de supprimer un message de contact
     *
     * @param Contact $contact
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/admin/contact/{id}/delete',name: 'admin_contact_delete')]
    public function delete(Contact $contact,EntityManagerInterface $manager):Response
    {
        $this->addFlash('danger','Le message de '.$contact->getFullName().' a bien été supprimé');

        $manager->remove($contact);
        $manager->flush();

        return $this->redirectToRoute('admin_contact_index');
    }

    /**
     * Permet d'afficher les messages avec ou sans recherche en étant paginé
     *
     * @param PaginationService $pagination
     * @param integer $page
     * @param ContactRepository $repo
     * @param Request $request
     * @param string $recherche
     * @return Response
     */
    #[Route('/admin/contact/{page<\d+>?1}/{recherche}', name: 'admin_contact_index')]
    public function index(PaginationService $pagination,int $page,ContactRepository $repo,Request $request,string $recherche = ""): Response
    {
        $pagination->setEntityClass(Contact::class)
                    ->setSearch($recherche)
                    ->setOrder(['status'=>'ASC'])
                    ->setPage($page)
                    ->setLimit(2);
        $messageNotSeen = $repo->findBy(['status'=>false]);
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
    
        return $this->render('admin/contact/index.html.twig', [
            'pagination' => $pagination,
            'notSeen' => Count($messageNotSeen),
            'formSearch' => $form->createView(),
        ]);
    }

}
