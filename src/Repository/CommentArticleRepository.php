<?php

namespace App\Repository;

use App\Entity\CommentArticle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method CommentArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentArticle[]    findAll()
 * @method CommentArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentArticleRepository extends ServiceEntityRepository
{
    private $manager;
    private $container;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $manager, ContainerInterface $container)
    {
        parent::__construct($registry, CommentArticle::class);
        $this->manager = $manager;
        $this->container = $container;
    }

    /**
     * Permet de récupérer tout les commentaires d'un article
     *
     * @return Query
     */
    public function findAllCommentsByArticle($request, $id) {
        $query = $this->manager->createQuery(
            'SELECT c FROM App\Entity\CommentArticle c WHERE c.article = :id ORDER BY c.createdAt DESC'
        );

        $query->setParameters(array(
            'id' => $id
        ));

        $pagenator = $this->container->get('knp_paginator');
        $results = $pagenator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return ($results);
    }

    /**
     * Permet de récupérer les commentaires d'articles signalés
     *
     * @return Query
     */
    public function findSignalComment($request)
    {
        $query = $this->manager->createQuery(
            'SELECT c, m
            FROM App\Entity\CommentArticle c
            JOIN c.moderation m
            WHERE m.statut = :statut
            ORDER BY c.createdAt DESC
            '
        );

        $query->setParameters(array(
            'statut' => 'Commentaire signalé'
        ));

        $pagenator = $this->container->get('knp_paginator');
        $results = $pagenator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return ($results);
    }

}
