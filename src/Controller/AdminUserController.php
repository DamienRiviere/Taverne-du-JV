<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminUserType;
use App\Service\Pagination;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    private $manager;
    private $pagination;

    public function __construct(EntityManagerInterface $manager, Pagination $pagination) {
        $this->manager = $manager;
        $this->pagination = $pagination;
    }

    /**
     * Affiche la liste des utilisateurs du site
     * 
     * @Route("/admin/users", name="admin_users_index")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(UserRepository $repo, Request $request)
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $this->pagination->paginate($repo->findAllUsers(), $request, 10)
        ]);
    }

    /**
     * Permet d'afficher les détails d'un compte utilisateur et permet de changer de rôle
     *
     * @Route("/admin/users/{id}", name="admin_users_show")
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param User $user
     * @return void
     */
    public function show(User $user, Request $request) {
        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($user);
            $this->manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Le nouveau rôle de l'utilisateur a bien été enregistrée !"
            );
        }

        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un utilisateur
     *
     * @Route("/admin/users/{id}/delete", name="admin_users_delete")
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function delete(User $user) {
        $this->manager->remove($user);
        $this->manager->flush();

        $this->addFlash(
            'red',
            "L'utilisateur a bien été supprimer !"
        );

        return $this->redirectToRoute('admin_users_index');
    }

}
