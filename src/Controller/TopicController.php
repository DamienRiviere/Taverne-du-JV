<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Entity\CommentTopic;
use App\Form\CommentTopicType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TopicController extends AbstractController
{
    /**
     * Permet d'afficher un topic et d'écrire un commentaire
     * 
     * @Route("/forum/{slugForum}/{id}/{slugTopic}", name="topic_show")
     */
    public function show(Topic $topic, Request $request, EntityManagerInterface $manager)
    {
        $comment = new CommentTopic();

        $user = $this->getUser();

        $form = $this->createForm(CommentTopicType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setUser($user)
                    ->setTopic($topic);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Votre commentaire a bien été envoyer !"
            );
        }

        return $this->render('forum/topic/show.html.twig', [
            'topic' => $topic,
            'form' => $form->createView()
        ]);
    }
}
