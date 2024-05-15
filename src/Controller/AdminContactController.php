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
     * Permet de récupérer et afficher les messages de contact
     *
     * @param PaginationService $pagination
     * @param integer $page
     * @return Response
     */
    #[Route('/admin/contact/{page<\d+>?1}', name: 'admin_contact_index')]
    public function index(int $page,ContactRepository $repo): Response
    {
        $limit = 1;
        $start = $page * $limit - $limit;
        $total = count($repo->findAll());
        $pages = ceil($total / $limit);
        $contact = $repo->findBy([],[],$limit,$start);
        $messageNotSeen = $repo->findBy(['status'=>false]);
        if($pages > 5){
            if($page == 1){
                $pageMax = 3;
                $pageMin = $page;
            }elseif($page == $pages){
                $pageMax = $pages;
                $pageMin = $page - 2;
            }else{
                $pageMax = ($page + 1);
                $pageMin = $page - 1;
            }
        }else{
            $pageMin = 1;
            $pageMax = 5;
        }
        return $this->render('admin/contact/index.html.twig', [
            'contacts' => $contact,
            'pagination' => $pages,
            'pageMin' => $pageMin,
            'pageMax' => $pageMax,
            'page' => $page,
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
