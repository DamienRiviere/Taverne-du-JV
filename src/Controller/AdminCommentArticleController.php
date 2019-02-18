<?php

namespace App\Controller;

use App\Entity\CommentArticle;
use App\Form\AdminCommentArticleType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentArticleController extends AbstractController
{
    /**
     * Permet d'afficher la page de modération des commentaires signalés
     * 
     * @Route("/admin/moderation/comment-article", name="admin_comment_article_index")
     */
    public function index(CommentArticleRepository $repo)
    {
        return $this->render('admin/moderation/comment_article/index.html.twig', [
            'comments' => $repo->findSignalComment()
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
    public function show(CommentArticle $comment, EntityManagerInterface $manager, Request $request) 
    {
        $form = $this->createForm(AdminCommentArticleType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($comment);
            $manager->flush();

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
    public function delete(CommentArticle $comment, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'red',
            "Le commentaire a bien été supprimer !"
        );

        return $this->redirectToRoute('admin_comment_article_index');
    }
}
