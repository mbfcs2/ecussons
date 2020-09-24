<?php
// src/Service/UserManager.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserManager
{
	private $em ;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function get_users_by_name(String $name)
    {
        $repository = $this->em->getRepository(User::class);

        $children = $repository->createQueryBuilder('o')
            ->where('o.login LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();

        return $children;
    }

    /**
     * Retourne les utilisateurs possédant un slug donné
     * (un seul au max !)
     * @param String $slug
     * @return mixed
     */
    public function get_users_by_slug(String $slug)
    {
        $repository = $this->em->getRepository(User::class);

        $children = $repository->createQueryBuilder('o')
            ->where('o.slug LIKE :slug')
            ->setParameter('slug', '%'.$slug.'%')
            ->getQuery()
            ->getResult();

        return $children;
    }
}