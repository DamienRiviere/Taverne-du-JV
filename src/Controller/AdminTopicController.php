<?php

namespace App\Controller;

use App\Repository\TopicRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTopicController extends AbstractController
{
    /**
     * Permet d'afficher la page de gestion des topics
     * 
     * @Route("/admin/topics", name="admin_topics_index")
     */
    public function index(TopicRepository $repo)
    {
        return $this->render('admin/topic/index.html.twig', [
            'topics' => $repo->findAll()
        ]);
    }
}
