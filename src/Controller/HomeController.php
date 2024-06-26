<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ServiceRepository;
use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
     /**
     * Permet d'afficher les services, les dernières meilleures évaluations et de traiter le formulaire de contact
     *
     * @param ServiceRepository $servicesRepo
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param StatsService $statsService
     * @return Response
     */
    #[Route('/', name: 'homepage')]
    public function index(ServiceRepository $servicesRepo,Request $request,EntityManagerInterface $manager,StatsService $statsService): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class,$contact);
        $form->handleRequest($request);
        $contact->setStatus(false);

        $evals = $statsService->bestEval();
        
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($contact);
            $manager->flush();
            $this->addFlash('success','Votre message a bien été envoyé');
            return new RedirectResponse($this->generateUrl('homepage').'#contact');
        }
        
        return $this->render('home.html.twig', [
            'services' => $servicesRepo->findAll(),
            'evaluations' => $evals,
            'formContact' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page de mentions légales
     *
     * @return Response
     */
    #[Route('/legals', name:"legals")]
    public function legals(): Response
    {
        return $this->render('legals/index.html.twig');
    }
}
