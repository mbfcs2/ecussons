<?php
// src/Service/DestinationManager.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Destination;
use Doctrine\Common\Collections\ArrayCollection;

class DestinationManager
{
	private $em ;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
	
    public function get_all_items_under(Destination $destination)
    {
        $items = $destination->getItems() ;
		$repository = $this->em->getRepository(Destination::class);
		
		$children = $repository->findBy(['parent' => $destination]);
		if ($children)
			foreach ($children as $child)
				$items = new ArrayCollection(array_merge($items->toArray(), $this->get_all_items_under($child)->toArray())) ;
			
		return $items;
    }
}