<?php

namespace App\Repository;

use App\Entity\CommentArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommentArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentArticle[]    findAll()
 * @method CommentArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommentArticle::class);
    }

    // /**
    //  * @return CommentArticle[] Returns an array of CommentArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentArticle
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
