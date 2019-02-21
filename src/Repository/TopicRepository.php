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

    public function __construct(RegistryInterface $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Topic::class);
        $this->manager = $manager;
    }

    /**
     * Permet de récupérer tout les topics
     *
     * @param [type] $request
     * @return Query
     */
    public function findAllTopics()
    {
        $query = $this->manager->createQuery('SELECT t FROM App\Entity\Topic t ORDER BY t.createdAt DESC');

        return $query->getResult();
    }

    /**
     * Permet de récupérer un topic à partir de son id
     *
     * @return Query
     */
    public function findTopic($id)
    {
        $query = $this->manager->createQuery('SELECT t FROM App\Entity\Topic t WHERE t.id = :id');

        $query->setParameters(array(
            'id' => $id,
        ));

        return $query->getResult();
    }

    /**
     * Permet de récupérer tout les topics d'un forum via son slug
     *
     * @param [type] $slug
     * 
     * @return Query
     */
    public function findForumAllTopicBySlug($slug) {
        $query = $this->manager->createQuery('SELECT t, f FROM App\Entity\Topic t JOIN t.forum f WHERE f.slug = :slug ORDER BY t.createdAt DESC');

        $query->setParameters(array(
            'slug' => $slug
        ));

        return $query->getResult();
    }

}
