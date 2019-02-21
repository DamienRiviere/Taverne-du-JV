<?php

namespace App\Controller;

use App\Service\Pagination;
use App\Entity\CommentArticle;
use App\Form\AdminCommentArticleType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentArticleController extends AbstractController
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
     * Permet d'afficher la page de modération des commentaires signalés
     * 
     * @Route("/admin/moderation/comment-article", name="admin_comment_article_index")
     */
    public function index(Request $request) {
        return $this->render('admin/moderation/comment_article/index.html.twig', [
            'comments' => $this->pagination->paginate($this->repo->findSignalComment(), $request, 10)
        ]);
    }

    /**
     * Permets d'afficher les commentaires qui ont été modérer
     *
     * @Route("/admin/moderation/comment-article/moderate", name="admin_comment_article_moderate")
     * 
     * @return void
     */
    public function moderateComments(Request $request) {
        return $this->render('admin/moderation/comment_article/moderate.html.twig', [
            'comments' => $this->pagination->paginate($this->repo->findModerateComment(), $request, 10)
        ]);
    }

    /**
     * Permet d'afficher le détails d'un commentaire et de le modérer
     *
     * @Route("/admin/moderation/comment-article/{id}", name="admin_comment_article_show")
     * 
     * @param CommentArticle $comment
     * @param EntityManagerInterface $manager
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
                "Le commentaire a bien été modérer !"
            );

            return $this->redirectToRoute('admin_comment_article_index');
        }

        return $this->render('admin/moderation/comment_article/show.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/moderation/comment-article/{id}/delete", name="admin_comment_article_delete")
     * 
     * @param CommentArticle $comment
     * @param EntityManagerInterface $manager
     * 
     * @return void
     */
    public function delete(CommentArticle $comment) {
        $this->manager->remove($comment);
        $this->manager->flush();

        $this->addFlash(
            'red',
            "Le commentaire a bien été supprimer !"
        );

        return $this->redirectToRoute('admin_comment_article_index');
    }

}