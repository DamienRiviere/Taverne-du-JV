<?php

namespace App\Controller;

use App\Entity\Topic;
use App\Service\Pagination;
use App\Entity\CommentTopic;
use App\Form\CommentTopicType;
use App\Repository\ModerationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentTopicRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TopicController extends AbstractController
{
    private $manager;
    private $pagination;

    public function __construct(EntityManagerInterface $manager, Pagination $pagination) {
        $this->manager = $manager;
        $this->pagination = $pagination;
    }

    /**
     * Permet d'afficher un topic et d'écrire un commentaire
     * 
     * @Route("/forum/{slugForum}/{id}/{slugTopic}", name="topic_show")
     */
    public function show(Topic $topic, Request $request, ModerationRepository $repoMode, CommentTopicRepository $repoComment, $id) {
        $comment = new CommentTopic();

        $user = $this->getUser();

        $form = $this->createForm(CommentTopicType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($user)
                    ->setTopic($topic)
                    ->setModeration($repoMode->findOneBy([
                        'statut' => 'Commentaire publié'
                    ]));
            
            $topic->setLastMsg(new \DateTime());

            $this->manager->persist($comment);
            $this->manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Votre commentaire a bien été envoyer !"
            );
        }

        return $this->render('forum/topic/show.html.twig', [
            'topic' => $topic,
            'form' => $form->createView(),
            'comments' => $this->pagination->paginate($repoComment->findTopicAllComments($id), $request, 15)
        ]);
    }
}
