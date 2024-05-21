<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminContactController extends AbstractController
{
    
    /**
     * Permet d'afficher les message avec une pagination
     *
     * @param integer $page
     * @param ContactRepository $repo
     * @return Response
     */
    #[Route('/admin/contact/{page<\d+>?1}', name: 'admin_contact_index')]
    public function index(PaginationService $pagination,int $page,ContactRepository $repo): Response
    {
        $pagination->setEntityClass(Contact::class)
                    ->setOrder(['status'=>'ASC'])
                    ->setPage($page)
                    ->setLimit(10);
        $messageNotSeen = $repo->findBy(['status'=>false]);
    
        return $this->render('admin/contact/index.html.twig', [
            'pagination' => $pagination,
            'notSeen' => Count($messageNotSeen)
        ]);
    }

    /**
     * Permet d'afficher un message
     *
     * @param Contact $contact
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
     * @return void
     */
    #[Route('/admin/contact/{id}/delete',name: 'admin_contact_delete')]
    public function delete(Contact $contact,EntityManagerInterface $manager){
        $this->addFlash('danger','Le message de '.$contact->getFullName().' a bien été supprimé');

        $manager->remove($contact);
        $manager->flush();

        return $this->redirectToRoute('admin_contact_index');
    }

}
