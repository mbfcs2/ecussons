<?php

namespace App\Controller;

use App\Entity\Destination;

use App\Entity\Item;
use App\Service\DestinationManager;
use App\Service\UserManager;
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
    public function search(Request $request, DestinationManager $DestinationManager, UserManager $UserManager): Response
    {
        $retour = [];
        $searching_term = $request->query->get('q');
        if (!is_null($searching_term)) {

            // Utilisateurs
            $users = $UserManager->get_users_by_name($searching_term);
            foreach ($users as $user) {
                $retour[] = [
                    'type' => 'user',
                    'name' => $user->getLogin(),
                    'arbo' => 'Utilisateur',
                    'nbitems' => $user->getItems()->count(),
                    'url' => $this->generateUrl('user_profile', [
                        'slug' => $user->getSlug()
                    ])
                ];
            }


            // Destinations
            $children = $DestinationManager->get_destinations_by_name($searching_term);
            foreach ($children as $child) {
                $retour[] = [
                    'type' => 'destination',
                    'name' => $child->getName(),
                    'arbo' => $child->affiche_arbo(),
                    'nbitems' => 0, #$child->getItems()->count(),
                    'url' => $this->generateUrl('destination_list', [
                        'slug' => $child->getSlug()
                    ])
                ];
            }


        }
        return $this->json($retour);
    }

}
