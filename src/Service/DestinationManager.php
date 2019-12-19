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
	
	public function get_all_destinations_under(Destination $destination)
    {
        $destinations = [$destination] ;
		$repository = $this->em->getRepository(Destination::class);
		
		$children = $repository->findBy(['parent' => $destination]);
		if ($children)
			foreach ($children as $child)
				$destinations = array_merge($destinations, $this->get_all_destinations_under($child)) ;
			
		return $destinations;
    }
	
    public function get_all_items_under(Destination $destination)
    {
        
		
		
		$repository = $this->em->getRepository(Item::class);
		
		$children = $repository->findBy(['destination' => $this->get_all_destinations_under($destination)]);
		
		return $children;
    }
}