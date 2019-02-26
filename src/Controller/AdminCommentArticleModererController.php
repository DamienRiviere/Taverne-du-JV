<?php

namespace App\Controller;

use App\Service\Pagination;
use App\Entity\CommentArticle;
use App\Form\AdminCommentArticleType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentArticleModererController extends AbstractController
{
    private $manager;
    private $repo;
    private $pagination;

    public function __construct(CommentArticleRepository $repo, EntityManagerInterface $manager, Pagination $pagination) {
        $this->manager = $manager;
        $this->repo = $repo;
        $this->pagination = $pagination;
    }

    /**
     * Permet d'afficher les commentaires d'articles modérés
     * 
     * @Route("/admin/moderation/comment-article/moderer", name="admin_comment_article_moderer_index")
     * @IsGranted("ROLE_MODERATOR")
     */
    public function index(Request $request) {
        return $this->render('admin/moderation/comment_article/moderer/index.html.twig', [
            'comments' => $this->pagination->paginate($this->repo->findModerateComment(), $request, 10)
        ]);
    }

    /**
     * Permet d'afficher le détails d'un commentaire et de le modérer
     *
     * @Route("/admin/moderation/comment-article/moderer/{id}", name="admin_comment_article_moderer_show")
     * @IsGranted("ROLE_MODERATOR")
     * 
     * @param CommentArticle $comment
     * @param Request $request
     * 
     * @return Response
     */
    public function show(CommentArticle $comment, Request $request) {
        $form = $this->createForm(AdminCommentArticleType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($comment);
            $this->manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Le nouveau statut du commentaire a bien été enregistrer !"
            );

            return $this->redirectToRoute('admin_comment_article_moderer_index');
        }

        return $this->render('admin/moderation/comment_article/moderer/show.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/moderation/comment-article/moderer/{id}/delete", name="admin_comment_article_moderer_delete")
     * @IsGranted("ROLE_MODERATOR")
     * 
     * @param CommentArticle $comment
     * @return void
     */
    public function delete(CommentArticle $comment) {
        $this->manager->remove($comment);
        $this->manager->flush();

        $this->addFlash(
            'red',
            "Le commentaire a bien été supprimer !"
        );

        return $this->redirectToRoute('admin_comment_article_moderer_index');
    }
}
