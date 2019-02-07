<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    private $repo;

    public function __construct(ArticleRepository $repo) {
        $this->repo = $repo;
    }

    /**
     * Permet de créer un article
     *
     * @Route("/article/create", name="article_create")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager) {

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);
        }

        return $this->render('article/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Permet de modifier un article
     *
     * @Route("/article/edit/{slug}", name="article_edit")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $manager) {
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher les news
     *
     * @Route("/article/news", name="article_news")
     * 
     * @return Response
     */
    public function news() {
        return $this->render('article/news.html.twig', [
            'articles' => $this->repo->returnNews()
        ]);
    }

    /**
     * Permet d'afficher les previews
     * 
     * @Route("/article/preview", name="article_preview")
     *
     * @return Response
     */
    public function preview() {
        return $this->render('article/preview.html.twig', [
            'articles' => $this->repo->returnPreview()
        ]);
    }

    /**
     * Permet d'afficher les tests
     *
     * @Route("/article/test", name="article_test")
     * 
     * @return Response
     */
    public function test() {
        return $this->render('article/test.html.twig', [
            'articles' => $this->repo->returnTest()
        ]);
    }

    /**
     * Permet d'afficher un article
     *
     * @Route("/article/{slug}", name="article_show")
     * 
     * @return Response
     */
    public function show(Article $article) {
        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }

    
}