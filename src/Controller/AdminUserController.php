<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddMemberType;
use App\Form\SearchType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * Permet de supprimer un utilisateur
     *
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('admin/user/{id}/delete',name:"admin_user_delete")]
    public function delete(User $user,EntityManagerInterface $manager):Response
    {
        $this->addFlash('success','L\'utilisateur '.$user->getUsername().' a bien été supprimé');

        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('admin_user_index');
    }

    /**
     * Permet d'ajouter le rôle Membre à un utilisateur
     *
     * @param User $user
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('admin/user/{id}/addMember',name:"admin_user_addMember")]
    public function addMember(User $user,EntityManagerInterface $manager,Request $request):Response
    {
        $form = $this->createForm(AddMemberType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $role = $form['roles']->getData();
            if(in_array('ROLE_MEMBER',$role)){
                $user->setRoles(['ROLE_USER','ROLE_MEMBER']);
                $this->addFlash('success','L\'utilisateur '.$user->getUsername().' a maintenant les rôles : User et membre');
            }else{
                $user->setRoles(['ROLE_USER']);
                $this->addFlash('success','L\'utilisateur '.$user->getUsername().' a maintenant le rôle : User');
            }
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/addMember.html.twig',[
            'user' => $user,
            'formRole' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher de manière paginer et avec une recherche sur les utilisateurs
     *
     * @param PaginationService $pagination
     * @param Request $request
     * @param integer $page
     * @param string $recherche
     * @return Response
     */
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
}
