<?php

namespace App\Controller;

use App\Entity\CommentTopic;
use App\Entity\CommentArticle;
use App\Repository\ModerationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModerationController extends AbstractController
{
    /**
     * Permet de signaler le commentaire d'un article
     * 
     * @Route("/moderation/signaler/comment-article/{id}", name="moderation_signal_comment_article")
     * @Security("is_granted('ROLE_USER') and user !== comment.getUser() ")
     * 
     */
    public function signalCommentArticle(CommentArticle $comment, ModerationRepository $repo, EntityManagerInterface $manager)
    {
        $comment->setModeration($repo->findOneBy([
            'statut' => 'Commentaire signalé'
        ]));

        $manager->persist($comment);
        $manager->flush();

        return $this->redirectToRoute('article_show', ['slug' => $comment->getArticle()->getSlug()]);
    }

    /**
     * Permet de signaler le commentaire d'un topic
     * 
     * @Route("moderation/signaler/comment-topic/{id}", name="moderation_signal_comment_topic")
     * @Security("is_granted('ROLE_USER') and user !== comment.getUser() ")
     *
     * @param CommentTopic $comment
     * @param ModerationRepository $repo
     * @param EntityManagerInterface $manager
     * 
     * @return void
     */
    public function signalCommentTopic(CommentTopic $comment, ModerationRepository $repo, EntityManagerInterface $manager)
    {
        $comment->setModeration($repo->findOneBy([
            'statut' => 'Commentaire signalé'
        ]));

        $manager->persist($comment);
        $manager->flush();

        return $this->redirectToRoute('topic_show', ['slugForum' => $comment->getTopic()->getForum()->getSlug(), 'id' => $comment->getTopic()->getId() ,'slugTopic' => $comment->getTopic()->getSlug()]);
    }
}
