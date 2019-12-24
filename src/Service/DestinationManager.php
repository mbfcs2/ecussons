<?php
// src/Service/DestinationManager.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Destination;
use App\Entity\Item;
use Doctrine\Common\Collections\ArrayCollection;

class DestinationManager
{
	private $em ;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function get_destinations_by_name(String $name)
    {
        $repository = $this->em->getRepository(Destination::class);

        $children = $repository->createQueryBuilder('o')
            ->where('o.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();

        return $children;
    }

    public function get_destinations_under(Destination $destination)
    {
        $repository = $this->em->getRepository(Destination::class);
        $children = $repository->findBy(['parent' => $destination], ['name' => 'asc']);

        return $children;
    }

    public function get_best_itemed_destinations_under(Destination $destination, int $limit=10)
    {
        $repository = $this->em->getRepository(Destination::class);
        # Il faudrait retourner les destinations avec le plus d'items
        $children = $repository->findBy(['parent' => $destination], ['name' => 'asc'], $limit);

        return $children;
    }


	public function get_all_destinations_under(Destination $destination, int $profondeur = 0)
    {
        $destinations = [] ;
		$repository = $this->em->getRepository(Destination::class);

		if ($profondeur > 0) {
		    $profondeur--;
		    $children = $repository->findBy(['parent' => $destination]);
            if ($children) {
                foreach ($children as $child) {
                    $destinations[] = $child;
                    $destinations = array_merge($destinations, $this->get_all_destinations_under($child, $profondeur)) ;
                }
            }
        }

		return $destinations;
    }
	
    public function get_all_items_under(Destination $destination, int $profondeur = 0)
    {
		$repository = $this->em->getRepository(Item::class);
        $children = $repository->findBy(['destination' => $this->get_all_destinations_under($destination, $profondeur)]);
		return $children;
    }
}