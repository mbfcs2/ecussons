<?php

namespace App\Controller;

use App\Entity\Destination;

use App\Entity\Item;
use App\Service\DestinationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

class SiteController extends AbstractController
{
    
    public function index(): Response
    {
        return $this->render('site/index.html.twig');
    }

    /**
     * @Route("/search", name="search", methods={"GET"})
     */
    public function search(Request $request, DestinationManager $DestinationManager): Response
    {
        $retour = [];
        $searching_term = $request->query->getAlnum('q');
        if (!is_null($searching_term)) {
            $children = $DestinationManager->get_destinations_by_name($searching_term);
            foreach ($children as $child) {
                $retour[] = [
                    'name' => $child->getName()
                ];
            }
        }
        return $this->json($retour);
    }

}
