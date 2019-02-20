<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Stats {

    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Permet de récupérer le nombre d'utilisateurs
     *
     * @return void
     */
    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    /**
     * Permet de récupérer le nombre d'articles
     *
     * @return void
     */
    public function getArticlesCount()
    {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Article a')->getSingleScalarResult();
    }

    /**
     * Permet de récupérer le nombre de commentaire dans les articles
     *
     * @return void
     */
    public function getArticleCommentsCount()
    {
        return $this->manager->createQuery('SELECT count(c) FROM App\Entity\CommentArticle c')->getSingleScalarResult();
    }

    /**
     * Permet de récupérer le nombre de commentaire signalé dans les articles
     *
     * @return Query
     */
    public function getArticleCommentsSignalCount()
    {
        $query = $this->manager->createQuery(
            '
            SELECT
                c, m
            FROM
                App\Entity\CommentArticle c
            JOIN
                c.moderation m
            WHERE
                m.statut = :statut
            ORDER BY
                c.createdAt DESC
            '
        );

        $query->setParameters(array(
            'statut' => 'Commentaire signalé'
        ));

        return $query->getResult();
    }

    /**
     * Permet de récupérer le nombre de topics
     *
     * @return void
     */
    public function getTopicsCount()
    {
        return $this->manager->createQuery('SELECT count(t) FROM App\Entity\Topic t')->getSingleScalarResult();
    }

    /**
     * Permet de récupérer le nombre de commentaires de topic
     *
     * @return void
     */
    public function getTopicCommentsCount()
    {
        return $this->manager->createQuery('SELECT count(c) FROM App\Entity\CommentTopic c')->getSingleScalarResult();
    }

    /**
     * Permet de récupérer le nombre de commentaire signalé dans les topics
     *
     * @return Query
     */
    public function getTopicCommentsSignalCount()
    {
        $query = $this->manager->createQuery(
            '
            SELECT
                c, m
            FROM
                App\Entity\CommentTopic c
            JOIN
                c.moderation m
            WHERE
                m.statut = :statut
            ORDER BY
                c.createdAt DESC
            '
        );

        $query->setParameters(array(
            'statut' => 'Commentaire signalé'
        ));

        return $query->getResult();
    }


}