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
                  ->setLimit(10);

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
    #[Route('admin/services/new',name:'admin_services_new')]
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
}
