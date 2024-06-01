<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SeanceController extends AbstractController
{
    /**
     * Permet à l'utilisateur d'ajouter une séance
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/seance/create', name: 'seance_create')]
    #[IsGranted('ROLE_USER')]
    public function create(EntityManagerInterface $manager, Request $request): Response
    {
        $seance = new Seance();

        $form = $this->createForm(SeanceType::class,$seance);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            foreach($seance->getExosMusculations() as $exosMuscu)
            {
                $exosMuscu->setSeance($seance);
                $manager->persist($exosMuscu);
            } foreach($seance->getExosCardios() as $exosCardio)
            {
                $exosCardio->setSeances($seance);
                $manager->persist($exosCardio);
            }

            $seance->setUser($this->getUser());

            $manager->persist($seance);
            $manager->flush();
            $this->addFlash('success','Votre séance : '.$seance->getName().' a bien été ajoutée');
            return $this->redirectToRoute('account_profil');
        }

        return $this->render('seance/index.html.twig', [
            'formSeance' => $form->createView(),
        ]);
    }

    /**
     * Permet d'afficher une séance
     *
     * @param Seance $seance
     * @return Response
     */
    #[Route('/seance/{id}/show',name:"seance_show")]
    #[IsGranted(
        attribute: New Expression('user == subject and is_granted("ROLE_USER")'),
        subject: New Expression('args["seance"].getUser()'),
        message: "Cette séance ne vous appartient pas"
    )]
    public function show(Seance $seance): Response
    {
        return $this->render('seance/show.html.twig',[
            'seance' => $seance
        ]);
    }
}
