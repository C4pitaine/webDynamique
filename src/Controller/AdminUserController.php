<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SearchType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    #[Route('/admin/user/{page<\d+>?1}/{recherche}', name: 'admin_user_index')]
    public function index(PaginationService $pagination,Request $request,int $page,string $recherche=""): Response
    {
        $pagination->setSearch($recherche)
                    ->setPage($page)
                    ->setEntityClass(User::class)
                    ->setLimit(10);

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
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
        }


        return $this->render('admin/user/index.html.twig', [
            'pagination' => $pagination,
            'formSearch' => $form->createView(),
        ]);
    }

    /**
     * Permet de supprimer un utilisateur
     *
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('admin/{id}/delete',name:"admin_user_delete")]
    public function delete(User $user,EntityManagerInterface $manager):Response
    {
        $this->addFlash('success','L\'utilisateur '.$user->getUsername().' a bien été supprimé');

        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('admin_user_index');
    }
}
