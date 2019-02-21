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

    public function __construct(RegistryInterface $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, CommentTopic::class);
        $this->manager = $manager;
    }

    /**
     * Permet de récupérer tout les commentaires d'un topic via son id
     *
     * @param [type] $id
     * 
     * @return Query
     */
    public function findTopicAllComments($id) {
        $query = $this->manager->createQuery(
            'SELECT c FROM App\Entity\CommentTopic c WHERE c.topic = :id'
        );

        $query->setParameters(array(
            'id' => $id
        ));

        return $query->getResult();
    }

    /**
     * Permet de récupérer les commentaires de topic signalés
     *
     * @return Query
     */
    public function findSignalComment()
    {
        $query = $this->manager->createQuery(
            'SELECT c, m FROM App\Entity\CommentTopic c JOIN c.moderation m WHERE m.statut = :statut ORDER BY c.createdAt DESC'
        );

        $query->setParameters(array(
            'statut' => 'Commentaire signalé'
        ));

        return $query->getResult();
    }

    /**
     * Permet de récupérer les commentaires de topic modéré
     *
     * @return Query
     */
    public function findModerateComment()
    {
        $query = $this->manager->createQuery(
            'SELECT c, m FROM App\Entity\CommentTopic c JOIN c.moderation m WHERE m.statut = :statut ORDER BY c.createdAt DESC'
        );

        $query->setParameters(array(
            'statut' => 'Commentaire modéré'
        ));

        return $query->getResult();
    }
      
}
