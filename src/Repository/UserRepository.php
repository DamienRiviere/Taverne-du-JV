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

    public function __construct(RegistryInterface $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, User::class);
        $this->manager = $manager;
    }

    /**
     * Permet de récupérer tout les utilisateurs
     *
     * @param [type] $request
     * 
     * @return Query
     */
    public function findAllUsers() {
        $query = $this->manager->createQuery('SELECT u FROM App\Entity\User u');

        return $query->getResult();
    }

    /**
     * Permet de récupérer tout les articles d'un utilisateur
     *
     * @param [type] $user
     * @return void
     */
    public function findUserArticles($user) {
        $query = $this->manager->createQuery('SELECT u, a FROM App\Entity\User u JOIN u.articles a WHERE a.author = :user  ORDER BY a.createdAt DESC');
        
        $query->setParameters(array(
            'author' => $user
        ));

        return $query->getResult();
    }
    
}
