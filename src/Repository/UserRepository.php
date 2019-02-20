<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $manager;
    private $container;

    public function __construct(RegistryInterface $registry, EntityManagerInterface $manager, ContainerInterface $container)
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
        $this->container = $container;
    }

    /**
     * Permet de récupérer tout les utilisateurs
     *
     * @param [type] $request
     * 
     * @return Query
     */
    public function findAllUsers($request) {
        $query = $this->manager->createQuery(
            '
            SELECT
                u
            FROM
                App\Entity\User u
            '
        );

        $pagenator = $this->container->get('knp_paginator');
        $results = $pagenator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return ($results);
    }
    
}
