<?php

namespace App\Repository;

use App\Entity\Moderation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Moderation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Moderation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Moderation[]    findAll()
 * @method Moderation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModerationRepository extends ServiceEntityRepository
{
    private $em;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Moderation::class);
        $this->em = $em;
    }
    
}
