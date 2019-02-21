<?php

namespace App\Controller;

use App\Service\Pagination;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $pagination;

    public function __construct(Pagination $pagination) {
        $this->pagination = $pagination;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $repo, Request $request)
    {
        return $this->render('home/index.html.twig', [
            'articles' => $this->pagination->paginate($repo->findAllArticles(), $request, 15),
            'tests' => $repo->findTest(),
            'previews' => $repo->findPreview()
        ]);
    }
}
