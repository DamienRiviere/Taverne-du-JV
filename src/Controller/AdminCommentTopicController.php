<?php

namespace App\Controller;

use App\Service\Pagination;
use App\Entity\CommentTopic;
use App\Form\AdminCommentTopicType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentTopicRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentTopicController extends AbstractController
{
    private $manager;
    private $repo;
    private $pagination;

    public function __construct(CommentTopicRepository $repo, EntityManagerInterface $manager, Pagination $pagination) {
        $this->manager = $manager;
        $this->repo = $repo;
        $this->pagination = $pagination;
    }

    /**
     * Permet d'afficher la page de modération des commentaires signalés
     * 
     * @Route("/admin/moderation/comment-topic", name="admin_comment_topic_index")
     */
    public function index(Request $request) {
        return $this->render('admin/moderation/comment_topic/index.html.twig', [
            'comments' => $this->pagination->paginate($this->repo->findSignalComment(), $request, 10)
        ]);
    }

    /**
     * Permets d'afficher les commentaires qui ont été modérer
     *
     * @Route("/admin/moderation/comment-topic/moderate", name="admin_comment_topic_moderate")
     * 
     * @return void
     */
    public function moderateComments(Request $request) {
        return $this->render('admin/moderation/comment_topic/moderate.html.twig', [
            'comments' => $this->pagination->paginate($this->repo->findModerateComment(), $request, 10)
        ]);
    }

    /**
     * Permet d'afficher le détails d'un commentaire et de le modérer
     *
     * @Route("/admin/moderation/comment-topic/{id}", name="admin_comment_topic_show")
     * 
     * @param CommentTopic $comment
     * @param EntityManagerInterface $manager
     * @param Request $request
     * 
     * @return void
     */
    public function show(CommentTopic $comment, Request $request) {
        $form = $this->createForm(AdminCommentTopicType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($comment);
            $this->manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Le commentaire a bien été modérer !"
            );

            return $this->redirectToRoute('admin_comment_topic_index');
        }

        return $this->render('admin/moderation/comment_topic/show.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/moderation/comment-topic/{id}/delete", name="admin_comment_topic_delete")
     * 
     * @param CommentTopic $comment
     * @param EntityManagerInterface $manager
     * 
     * @return void
     */
    public function delete(CommentTopic $comment) {
        $this->manager->remove($comment);
        $this->manager->flush();

        $this->addFlash(
            'red',
            "Le commentaire a bien été supprimer !"
        );

        return $this->redirectToRoute('admin_comment_topic_index');
    }
}
