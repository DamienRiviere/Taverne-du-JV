<?php

namespace App\Controller;

use App\Entity\CommentTopic;
use App\Form\CommentTopicType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentTopicController extends AbstractController
{
    /**
     * Permet de modifier un commentaire d'un topic
     * 
     * @Route("/comment-topic/edit/{id}", name="comment_topic_edit")
     * @Security("is_granted('ROLE_USER') and user === comment.getUser()")
     */
    public function edit(CommentTopic $comment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(CommentTopicType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Votre commentaire a bien été modifier !"
            );

            return $this->redirectToRoute('topic_show', [
                'slugForum' => $comment->getTopic()->getForum()->getSlug(),
                'id' => $comment->getTopic()->getId(),
                'slugTopic' => $comment->getTopic()->getSlug()
            ]);
        }

        return $this->render('forum/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }
}
