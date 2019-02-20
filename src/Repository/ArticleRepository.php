<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    private $manager;
    private $container;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $manager, ContainerInterface $container)
    {
        parent::__construct($registry, Article::class);
        $this->manager = $manager;
        $this->container = $container;
    }

    /**
     * Permet de récupérer tout les articles
     *
     * @return Query
     */
    public function returnAllArticle($request) 
    {
        $query = $this->manager->createQuery(
            'SELECT a FROM App\Entity\Article a ORDER BY a.createdAt DESC'
        );

        $pagenator = $this->container->get('knp_paginator');
        $results = $pagenator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 15)
        );

        return ($results);
    }

    /**
     * Permet de récupérer les tests
     *
     * @return Query
     */
    public function returnTest($request)
    {
        $query = $this->manager->createQuery(
            'SELECT a, c 
            FROM App\Entity\Article a 
            JOIN a.category c
            WHERE c.title = :test
            ORDER BY a.createdAt DESC
            '
        );

        $query->setParameters(array(
            'test' => 'Test'
        ));

        $pagenator = $this->container->get('knp_paginator');
        $results = $pagenator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 15)
        );

        return ($results);
    }

    /**
     * Permet de récupérer les previews
     *
     * @return Query
     */
    public function returnPreview($request)
    {
        $query = $this->manager->createQuery(
            'SELECT a, c
            FROM App\Entity\Article a
            JOIN a.category c
            WHERE c.title = :preview
            ORDER BY a.createdAt DESC
            '
        );

        $query->setParameters(array(
            'preview' => 'Preview'
        ));

        $pagenator = $this->container->get('knp_paginator');
        $results = $pagenator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 15)
        );

        return ($results);
    }

    /**
     * Permet de récupérer les news
     *
     * @return Query
     */
    public function returnNews($request)
    {
        $query = $this->manager->createQuery(
            'SELECT a, c
            FROM App\Entity\Article a
            JOIN a.category c
            WHERE c.title = :news
            ORDER BY a.createdAt DESC
            '
        );

        $query->setParameters(array(
            'news' => 'News'
        ));

        $pagenator = $this->container->get('knp_paginator');
        $results = $pagenator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 15)
        );

        return ($results);
    }

}
