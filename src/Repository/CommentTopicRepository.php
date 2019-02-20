<?php

namespace App\Repository;

use App\Entity\CommentTopic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method CommentTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentTopic[]    findAll()
 * @method CommentTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentTopicRepository extends ServiceEntityRepository
{
    private $manager;
    private $container;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $manager, ContainerInterface $container)
    {
        parent::__construct($registry, CommentTopic::class);
        $this->manager = $manager;
        $this->container = $container;
    }

    /**
     * Permet de récupérer tout les commentaires d'un topic
     *
     * @param [type] $request
     * @param [type] $id
     * 
     * @return Query
     */
    public function findAllCommentsByTopic($request, $id) {
        $query = $this->manager->createQuery(
            'SELECT c FROM App\Entity\CommentTopic c WHERE c.topic = :id ORDER BY c.createdAt DESC'
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
     * Permet de récupérer les commentaires de topic signalés
     *
     * @return Query
     */
    public function findSignalComment($request)
    {
        $query = $this->manager->createQuery(
            'SELECT c, m FROM App\Entity\CommentTopic c JOIN c.moderation m WHERE m.statut = :statut ORDER BY c.createdAt DESC'
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
