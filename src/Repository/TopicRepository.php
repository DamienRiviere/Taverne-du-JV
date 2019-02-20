<?php

namespace App\Repository;

use App\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Topic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topic[]    findAll()
 * @method Topic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicRepository extends ServiceEntityRepository
{
    private $manager;
    private $container;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $manager, ContainerInterface $container)
    {
        parent::__construct($registry, Topic::class);
        $this->manager = $manager;
        $this->container = $container;
    }

    /**
     * Permet de récupérer tout les topics
     *
     * @param [type] $request
     * @return Query
     */
    public function returnAllTopics($request)
    {
        $query = $this->manager->createQuery(
            'SELECT t FROM App\Entity\Topic t ORDER BY t.createdAt DESC'
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
     * Permet de récupérer un topic à partir de son id
     *
     * @return Query
     */
    public function findTopic($id)
    {
        $query = $this->manager->createQuery(
            'SELECT t FROM App\Entity\Topic t WHERE t.id = :id'
        );

        $query->setParameters(array(
            'id' => $id,
        ));

        return $query->getResult();
    }

}
