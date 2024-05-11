<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminContactController extends AbstractController
{
    /**
     * Permet de récupérer et afficher les messages de contact
     *
     * @param PaginationService $pagination
     * @param integer $page
     * @return Response
     */
    #[Route('/admin/contact/{page<\d+>?1}', name: 'admin_contact_index')]
    public function index(PaginationService $pagination,int $page): Response
    {
        $pagination->setEntityClass(Contact::class)
                ->setPage($page)
                ->setLimit(10);

        return $this->render('admin/contact/index.html.twig', [
            'pagination' => $pagination,
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

        $this->redirectToRoute('admin_contact_index');
    }

}
