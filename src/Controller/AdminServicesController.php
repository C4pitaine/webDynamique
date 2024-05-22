<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminServicesController extends AbstractController
{
    /**
     * Permet de récupérer tout les services
     *
     * @param PaginationService $pagination
     * @param integer $page
     * @return Response
     */
    #[Route('/admin/services/{page<\d+>?1}', name: 'admin_services_index')]
    public function index(PaginationService $pagination,int $page): Response
    {
        $pagination->setEntityClass(Service::class)
                  ->setPage($page)
                  ->setLimit(1)
                  ->setSearch("");

        return $this->render('admin/services/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Ajout d'un service
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('admin/services/create', name: "admin_services_new")]
    public function create(Request $request,EntityManagerInterface $manager):Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class,$service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($service);
            $manager->flush();

            $this->addFlash('success','Le service a bien été ajouté');

            return $this->redirectToRoute('admin_services_index');
        }

        return $this->render('admin/services/new.html.twig',[
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Modification d'un service
     *
     * @param Service $service
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return void
     */
    #[Route('/admin/services/{id}/update',name:'admin_services_update')]
    public function update(Service $service,Request $request,EntityManagerInterface $manager)
    {
        $form = $this->createForm(ServiceType::class,$service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($service);
            $manager->flush();

            $this->addFlash('warning','Le service '.$service->getTitle().' a bien été mis à jour');

            return $this->redirectToRoute('admin_services_index');
        }

        return $this->render('admin/services/update.html.twig',[
            'service' => $service,
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Suppression d'un service
     *
     * @param Service $service
     * @param EntityManagerInterface $manager
     * @return void
     */
    #[Route('admin/services/{id}/delete',name:'admin_services_delete')]
    public function delete(Service $service,EntityManagerInterface $manager){
        $this->addFlash('danger','Le service '.$service->getTitle().' a bien été supprimé');

        $manager->remove($service);
        $manager->flush();

        return $this->redirectToRoute('admin_services_index');
    }
}
