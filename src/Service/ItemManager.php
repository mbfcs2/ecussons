<?php
// src/Service/UserManager.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Item;
use phpDocumentor\Reflection\Types\Integer;

class ItemManager
{
	private $em ;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function get_random_items(int $howMany)
    {
        $repository = $this->em->getRepository(Item::class);

        $items=$repository->createQueryBuilder('items')
            ->join('items.illustrations', 'ill')
            ->join('items.destination', 'dest')
            ->getQuery()
            ->getResult();


        shuffle($items);

        return array_slice($items, 0, $howMany);
    }
}