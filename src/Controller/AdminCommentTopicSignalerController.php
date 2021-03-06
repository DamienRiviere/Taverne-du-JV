<?php

namespace App\Controller;

use App\Service\Pagination;
use App\Entity\CommentTopic;
use App\Form\AdminCommentTopicType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentTopicRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentTopicSignalerController extends AbstractController
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
     * @Route("/admin/moderation/comment-topic/signaler", name="admin_comment_topic_signaler_index")
     * @IsGranted("ROLE_MODERATOR")
     */
    public function index(Request $request) {
        return $this->render('admin/moderation/comment_topic/signaler/index.html.twig', [
            'comments' => $this->pagination->paginate($this->repo->findSignalComment(), $request, 10)
        ]);
    }

    /**
     * Permet d'afficher le détails d'un commentaire et de le modérer
     *
     * @Route("/admin/moderation/comment-topic/signaler/{id}", name="admin_comment_topic_signaler_show")
     * @IsGranted("ROLE_MODERATOR")
     * 
     * @param CommentTopic $comment
     * @param Request $request
     * 
     * @return Response
     */
    public function show(CommentTopic $comment, Request $request) {
        $form = $this->createForm(AdminCommentTopicType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($comment);
            $this->manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Le nouveau statut du commentaire a bien été enregistrer !"
            );

            return $this->redirectToRoute('admin_comment_topic_signaler_index');
        }

        return $this->render('admin/moderation/comment_topic/signaler/show.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/moderation/comment-topic/signaler/{id}/delete", name="admin_comment_topic_signaler_delete")
     * @IsGranted("ROLE_MODERATOR")
     * 
     * @param CommentTopic $comment
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

        return $this->redirectToRoute('admin_comment_topic_signaler_index');
    }
}
