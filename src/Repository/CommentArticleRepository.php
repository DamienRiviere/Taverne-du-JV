<?php

namespace App\Repository;

use App\Entity\CommentArticle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method CommentArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentArticle[]    findAll()
 * @method CommentArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentArticleRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, CommentArticle::class);
        $this->em = $em;
    }

    /**
     * Permet de récupérer les commentaires d'articles signalés
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

}
