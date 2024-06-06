<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{

    /**
     * Permet de supprimer un commentaire
     *
     * @param EntityManagerInterface $manager
     * @param Commentaire $commentaire
     * @return Response
     */
    #[Route('/commentaire/{id}/delete', name:"commentaire_delete")]
    #[IsGranted(
        attribute: New Expression('(user == subject and is_granted("ROLE_USER")) or is_granted("ROLE_ADMIN")'),
        subject: New Expression('args["commentaire"].getUser()'),
        message: "Ce commentaire ne vous appartient pas"
    )]
    public function delete(EntityManagerInterface $manager,Commentaire $commentaire,UserRepository $userRepo): Response
    {
        $this->addFlash('success','Votre commentaire a bien été supprimé');
        $sujet = $commentaire->getSujet();
        $user = $userRepo->findBy(['email'=>'anonyme@noreply.be']);
        $commentaire->setMessage('Ce commentaire a été supprimé')
                    ->setUser($user[0]);

        $manager->persist($commentaire);
        $manager->flush();

        return $this->redirectToRoute('forum_show',['slug'=>$sujet->getSlug()]);
    }
}
