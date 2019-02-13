<?php

namespace App\Repository;

use App\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Topic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topic[]    findAll()
 * @method Topic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Topic::class);
        $this->em = $em;
    }

    /**
     * Permet de récupérer un topic à partir de son id
     *
     * @return Query
     */
    public function findTopic($id)
    {
        $query = $this->em->createQuery(
            '
            SELECT
                t
            FROM App\Entity\Topic t
            WHERE
                t.id = :id
            '
        );

        $query->setParameters(array(
            'id' => $id,
        ));

        return $query->getResult();
    }

}
