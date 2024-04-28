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
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
    #[Route('admin/services/create', name: "admin_services_new")]
    public function create(Request $request,EntityManagerInterface $manager):Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class,$service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            //gestion de l'image
            $file = $form['logo']->getData(); // récupère les information de l'image
            if(!empty($file))
            {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename); // permet avec le premier paramètre de donner des informations sur comment gérer mes éléments, Any-Latin enlève les caractères spéciaux
                $newFilename = $safeFilename."-".uniqid().'.'.$file->guessExtension();
                try{
                    $file->move(
                        $this->getParameter('uploads_directory'), //où on va l'envoyer
                        $newFilename // qui on envoit
                    );
                }catch(FileException $e)
                {
                    return $e->getMessage();
                }
                $service->setLogo($newFilename);
            }
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
