<?php

namespace App\Controller;

use App\Service\Stats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * Permet d'afficher le dashboard
     * 
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, Stats $stats)
    {
        $users = $stats->getUsersCount();
        $articles = $stats->getArticlesCount();
        $commentsArticle = $stats->getArticleCommentsCount();
        $commentsArticleSignal = $stats->getArticleCommentsSignalCount();
        $topics = $stats->getTopicsCount();
        $commentsTopic = $stats->getTopicCommentsCount();
        $commentsTopicSignal = $stats->getTopicCommentsSignalCount();

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => compact('users', 'articles', 'commentsArticle', 'commentsArticleSignal', 'topics', 'commentsTopic', 'commentsTopicSignal')
        ]);
    }
}
