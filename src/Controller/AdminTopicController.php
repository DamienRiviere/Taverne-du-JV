<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Form\TopicType;
use App\Service\Pagination;
use App\Entity\CommentTopic;
use App\Form\CommentTopicType;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentTopicRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTopicController extends AbstractController
{
    /**
     * Permet d'afficher la page de gestion des topics
     * 
     * @Route("/admin/topics", name="admin_topics_index")
     */
    public function index(TopicRepository $repo, Request $request)
    {
        return $this->render('admin/topic/index.html.twig', [
            'topics' => $repo->returnAllTopics($request)
        ]);
    }

    /**
     * Permet d'éditer un topic
     * 
     * @Route("/admin/topics/{id}/edit", name="admin_topics_edit")
     *
     * @param Topic $topic
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    public function edit(Topic $topic, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($topic);
            $manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Le topic <strong>{$topic->getTitle()}</strong> a bien été modifié !"
            );
        }

        return $this->render('admin/topic/edit.html.twig', [
            'topic' => $topic,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un topic
     *
     * @Route("/admin/topics/{id}/delete", name="admin_topics_delete")
     * 
     * @param Topic $topic
     * @param EntityManagerInterface $manager
     * 
     * @return void
     */
    public function delete(Topic $topic, EntityManagerInterface $manager)
    {
        $manager->remove($topic);
        $manager->flush();

        $this->addFlash(
            'red',
            "Le topic a bien été supprimer !"
        );

        return $this->redirectToRoute('admin_topics_index');
    }

    /**
     * Permet d'afficher les commentaires d'un topic
     * 
     * @Route("/admin/topics/{id}/comments", name="admin_topics_comments")
     *
     * @param Topic $topic
     * 
     * @return Response
     */
    public function showComments(CommentTopicRepository $repo, Request $request, $id)
    {
        return $this->render('admin/topic/comment/show.html.twig', [
            'comments' => $repo->findAllCommentsByTopic($request, $id)
        ]);
    }

    /**
     * Permet de modifier un commentaire d'un topic
     *
     * @Route("/admin/topics/{idTopic}/comments/{idComment}/edit", name="admin_topics_comments_edit")
     * 
     * @param CommentTopic $idComment
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    public function editComment(CommentTopic $idComment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(CommentTopicType::class, $idComment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($idComment);
            $manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Le commentaire a bien été modifié !"
            );
        }

        return $this->render('admin/topic/comment/edit.html.twig', [
            'comment' => $idComment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire d'un topic
     *
     * @Route("/admin/topics/{idTopic}/comments/{idComment}/delete", name="admin_topics_comments_delete")
     * 
     * @param CommentTopic $idComment
     * @param EntityManagerInterface $manager
     * 
     * @return void
     */
    public function deleteComment(CommentTopic $idComment, EntityManagerInterface $manager)
    {
        $manager->remove($idComment);
        $manager->flush();

        $this->addFlash(
            'red',
            "Le commentaire a bien été supprimer !"
        );

        return $this->redirectToRoute('admin_topics_comments', ['id' => $idComment->getTopic()->getId()]);
    }
}
