<?php

namespace App\Repository;

use App\Entity\Forum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Forum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Forum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Forum[]    findAll()
 * @method Forum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumRepository extends ServiceEntityRepository
{
    private $em;
    
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Forum::class);
        $this->em = $em;
    }

    /**
     * Permet de récupérer les forums Nintendo suivant le type de plateforme (Console de salon ou portable)
     *
     * @return Query
     */
    public function findForumByPlatformType($platformTitle, $platformType)
    {
        $query = $this->em->createQuery(
            '
            SELECT
                f
            FROM App\Entity\Forum f
            WHERE
                f.platform_title = :title
            AND
                f.platform_type = :type
            '
        );

        $query->setParameters(array(
            'title' => $platformTitle,
            'type' => $platformType
        ));

        return $query->getResult();
    }

}
