<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Entity\Topic;
use App\Form\TopicType;
use App\Service\Pagination;
use App\Repository\ForumRepository;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForumController extends AbstractController
{
    private $manager;
    private $pagination;

    public function __construct(EntityManagerInterface $manager, Pagination $pagination) {
        $this->manager = $manager;
        $this->pagination = $pagination;
    }

    /**
     * Permet d'affichier la page de tous les forums
     * 
     * @Route("/forum", name="forum_index")
     */
    public function index(ForumRepository $repo) {
        return $this->render('forum/index.html.twig', [
            'nintendoHybrides' => $repo->findForumByPlatformType("Nintendo", "Hybride"),
            'nintendoSalons' => $repo->findForumByPlatformType("Nintendo", "Console de salon"),
            'nintendoPortables' => $repo->findForumByPlatformType("Nintendo", "Console portable"),
            'playstationSalons' => $repo->findForumByPlatformType("Playstation", "Console de salon"),
            'playstationPortables' => $repo->findForumByPlatformType("Playstation", "Console portable"),
            'xboxSalons' => $repo->findForumByPlatformType("Xbox", "Console de salon"),
            'pcForums' => $repo->findForumByPlatformType("PC", "PC")
        ]);
    }

    /**
     * Permet d'afficher un forum et de créer un topic
     *
     * @Route("/forum/{slug}", name="forum_show")
     * 
     * @param TopicRepository $repo
     * @return void
     */
    public function show(Forum $forum, Request $request, TopicRepository $repo, $slug) {
        $topic = new Topic();

        $user = $this->getUser();

        $form = $this->createForm(TopicType::class, $topic);

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()) {
            $topic->setUser($user)
                  ->setForum($forum);

            $this->manager->persist($topic);
            $this->manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Votre topic vient d'être créé !"
            );

            return $this->redirectToRoute(
                'topic_show', [
                    'slugForum' => $topic->getForum()->getSlug(),
                    'id' => $topic->getId(),
                    'slugTopic' => $topic->getSlug()
                ]
            );
        }

        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
            'form' => $form->createView(),
            'topics' => $this->pagination->paginate($repo->findForumAllTopicBySlug($slug), $request, 20)
        ]);
    }
}
