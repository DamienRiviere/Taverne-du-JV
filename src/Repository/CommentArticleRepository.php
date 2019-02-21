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

    public function __construct(RegistryInterface $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, CommentArticle::class);
        $this->manager = $manager;
    }

    /**
     * Permet de récupérer tout les commentaires d'un article via son id
     *
     * @return Query
     */
    public function findArticleAllCommentsWithId($id) {
        $query = $this->manager->createQuery('SELECT c FROM App\Entity\CommentArticle c WHERE c.article = :id ORDER BY c.createdAt DESC');

        $query->setParameters(array(
            'id' => $id
        ));

        return $query->getResult();
    }

    /**
     * Permet de récupérer tout les commentaires d'un article via son slug
     *
     * @param [type] $slug
     * 
     * @return Query
     */
    public function findArticleAllCommentsWithSlug($slug) {
        $query = $this->manager->createQuery('SELECT c, a FROM App\Entity\CommentArticle c JOIN c.article a WHERE a.slug = :slug ORDER BY c.createdAt DESC');

        $query->setParameters(array(
            'slug' => $slug
        ));

        return $query->getResult();
    }

    /**
     * Permet de récupérer les commentaires d'articles signalés
     *
     * @return Query
     */
    public function findSignalComment()
    {
        $query = $this->manager->createQuery('SELECT c, m FROM App\Entity\CommentArticle c JOIN c.moderation m WHERE m.statut = :statut ORDER BY c.createdAt DESC');

        $query->setParameters(array(
            'statut' => 'Commentaire signalé'
        ));

        return $query->getResult();
    }

    /**
     * Permet de récupérer les commentaires modérés des articles
     *
     * @return void
     */
    public function findModerateComment()
    {
        $query = $this->manager->createQuery('SELECT c, m FROM App\Entity\CommentArticle c JOIN c.moderation m WHERE m.statut = :statut ORDER BY c.createdAt DESC');

        $query->setParameters(array(
            'statut' => 'Commentaire modéré'
        ));

        return $query->getResult();
    }

}
