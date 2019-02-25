<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\Pagination;
use App\Entity\CommentArticle;
use App\Form\CommentArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminArticleController extends AbstractController
{
    private $manager;
    private $pagination;

    public function __construct(EntityManagerInterface $manager, Pagination $pagination) {
        $this->manager = $manager;
        $this->pagination = $pagination;
    }

    /**
     * Permet d'afficher la page de gestion des articles
     * 
     * @Route("/admin/articles", name="admin_articles_index")
     * @IsGranted("ROLE_AUTHOR")
     * 
     * @param ArticleRepository $repo
     * @param Request $request
     */
    public function index(ArticleRepository $repo, Request $request) {
        return $this->render('admin/article/index.html.twig', [
            'articles' => $this->pagination->paginate($repo->findAllArticles(), $request, 15)
        ]);
    }

    /**
     * Permet d'éditer un article
     * 
     * @Route("/admin/articles/{id}/edit", name="admin_articles_edit")
     * @IsGranted("ROLE_AUTHOR")
     *
     * @param Article $article
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
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un article
     *
     * @Route("/admin/articles/{id}/delete", name="admin_articles_delete")
     * @IsGranted("ROLE_AUTHOR")
     * 
     * @param Article $article
     * @param EntityManagerInterface $manager
     * 
     * @return void
     */
    public function delete(Article $article) {
        $this->manager->remove($article);
        $this->manager->flush();

        $this->addFlash(
            'red',
            "L'article a bien été supprimer !"
        );

        return $this->redirectToRoute('admin_articles_index');
    }

    /**
     * Permet d'afficher les commentaires d'un article
     * 
     * @Route("/admin/articles/{id}/comments", name="admin_articles_comments")
     * @IsGranted("ROLE_AUTHOR")
     *
     * @param CommentArticleRepository $repo
     * 
     * @return Response
     */
    public function showComments(CommentArticleRepository $repo, Request $request, $id) {
        return $this->render('admin/article/comment/show.html.twig', [
            'comments' => $this->pagination->paginate($repo->findArticleAllCommentsWithId($id), $request, 10)
        ]);
    }

    /**
     * Permet de modifier un commentaire d'un article
     *
     * @Route("/admin/articles/{idArticle}/comments/{idComment}/edit", name="admin_articles_comments_edit")
     * @IsGranted("ROLE_AUTHOR")
     * 
     * @param CommentArticle $idComment
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     * @return Response
     */
    public function editComment(CommentArticle $idComment, Request $request) {
        $form = $this->createForm(CommentArticleType::class, $idComment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($idComment);
            $this->manager->flush();

            $this->addFlash(
                'green lighten-1',
                "Le commentaire a bien été modifié !"
            );
        }

        return $this->render('admin/article/comment/edit.html.twig', [
            'comment' => $idComment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire d'un article
     *
     * @Route("/admin/articles/{idArticle}/comments/{idComment}/delete", name="admin_articles_comments_delete")
     * @IsGranted("ROLE_AUTHOR")
     * 
     * @param CommentArticle $comment
     * @return void
     */
    public function deleteComment(CommentArticle $idComment) {
        $this->manager->remove($idComment);
        $this->manager->flush();

        $this->addFlash(
            'red',
            "Le commentaire a bien été supprimer !"
        );

        return $this->redirectToRoute('admin_articles_comments', ['id' => $idComment->getArticle()->getId()]);
    }

}
