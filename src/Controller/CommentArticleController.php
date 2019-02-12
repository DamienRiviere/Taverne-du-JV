<?php

namespace App\Controller;

use App\Entity\CommentArticle;
use App\Form\CommentArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentArticleController extends AbstractController
{
    /**
     * Permet de modifier un commentaire
     * 
     * @Route("/comment/edit/{id}", name="comment_article_edit")
     * @Security("is_granted('ROLE_USER') and user === comment.getUser()")
     * 
     */
    public function edit(CommentArticle $comment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(CommentArticleType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($comment);
            $manager->flush();

            $this->AddFlash(
                'green lighten-1',
                "Votre commentaire a bien été modifier !"
            );

            return $this->redirectToRoute('article_show', ['slug' => $comment->getArticle()->getSlug()]);
        }

        return $this->render('comment_article/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }
}
