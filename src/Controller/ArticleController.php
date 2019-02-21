<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\Pagination;
use App\Entity\CommentArticle;
use App\Form\CommentArticleType;
use App\Repository\ArticleRepository;
use App\Repository\ModerationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    private $manager;
    private $repo;
    private $pagination;

    public function __construct(ArticleRepository $repo, EntityManagerInterface $manager, Pagination $pagination) {
        $this->repo = $repo;
        $this->manager = $manager;
        $this->pagination = $pagination;
    }

    /**
     * Permet de créer un article
     *
     * @Route("/article/create", name="article_create")
     * @IsGranted("ROLE_AUTHOR")
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    public function create(Request $request) {
        $article = new Article();

        $user = $this->getUser();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($user);

            $this->manager->persist($article);
            $this->manager->flush();

            $this->addFlash(
                'green lighten-1',
                "L'article <strong>{$article->getTitle()}</strong> a bien été enregistrée !"
            );

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
     * @IsGranted("ROLE_AUTHOR")
     * 
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    public function edit(Article $article, Request $request) {
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($article);
            $this->manager->flush();

            $this->addFlash(
                'green lighten-1',
                "L'article <strong>{$article->getTitle()}</strong> a bien été modifié !"
            );

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
    public function news(Request $request) {
        return $this->render('article/category/news.html.twig', [
            'articles' => $this->pagination->paginate($this->repo->findNews(), $request, 15),
            'tests' => $this->repo->findTest(),
            'previews' => $this->repo->findPreview()
        ]);
    }

    /**
     * Permet d'afficher les previews
     * 
     * @Route("/article/preview", name="article_preview")
     *
     * @return Response
     */
    public function preview(Request $request) {
        return $this->render('article/category/preview.html.twig', [
            'articles' => $this->pagination->paginate($this->repo->findPreview(), $request, 15),
            'news' => $this->repo->findNews(),
            'tests' => $this->repo->findTest()
        ]);
    }

    /**
     * Permet d'afficher les tests
     *
     * @Route("/article/test", name="article_test")
     * 
     * @return Response
     */
    public function test(Request $request) {
        return $this->render('article/category/test.html.twig', [
            'articles' => $this->pagination->paginate($this->repo->findTest(), $request, 15),
            'previews' => $this->repo->findPreview(),
            'news' => $this->repo->findNews()
        ]);
    }

    /**
     * Permet d'afficher un article et d'écrire des commentaires
     *
     * @Route("/article/{slug}", name="article_show")
     * 
     * @return Response
     */
    public function show(Article $article, Request $request, ModerationRepository $repoMode, CommentArticleRepository $repoComment, $slug) {

        $comment = new CommentArticle();

        $user = $this->getUser();

        $form = $this->createForm(CommentArticleType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setUser($user);
            $comment->setArticle($article)
                    ->setModeration($repoMode->findOneBy([
                        'statut' => 'Commentaire publié'
                    ]));

            $this->manager->persist($comment);
            $this->manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Votre commentaire a bien été envoyer !"
            );
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'comments' => $this->pagination->paginate($repoComment->findArticleAllCommentsWithSlug($slug), $request, 15),
            'tests' => $this->repo->findTest(),
            'previews' => $this->repo->findPreview()
        ]);
    }
 
}
