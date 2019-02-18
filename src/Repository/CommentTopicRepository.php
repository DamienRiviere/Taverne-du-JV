<?php

namespace App\Repository;

use App\Entity\CommentTopic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method CommentTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentTopic[]    findAll()
 * @method CommentTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentTopicRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, CommentTopic::class);
        $this->em = $em;
    }

    /**
     * Permet de récupérer les commentaires de topic signalés
     *
     * @return Query
     */
    public function findSignalComment()
    {
        $query = $this->em->createQuery(
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
