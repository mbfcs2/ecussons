<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
	
    public function show(String $slug): Response
    {
		$repository = $this->getDoctrine()->getRepository(User::class);
		$user = $repository->findOneBy(['slug' => $slug]);
		if (!$user) {
			throw $this->createNotFoundException(
				'No product found for slug '.$slug
			);
		}
		return $this->render('user/show.html.twig', [
            'utilisateur' => $user,
        ]);
    }
	
	 /**
     * @Route("/gotitem/{id}", name="user_gotitem", methods={"GET"})
     */
	public function gotitem(int $id): Response
    {
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		$request = Request::createFromGlobals();
		
		$user = $this->getUser();
		
		$entityManager = $this->getDoctrine()->getManager();
		$itemtoadd = $entityManager->getRepository(Item::class)->find($id);
		
		
		if ($user->getItems()->contains($itemtoadd)) {
			$user->removeItem($itemtoadd) ;
			$gotit = false ;
		}
		else {
			$user->addItem($itemtoadd) ;
			$gotit = true ;
		}
		
		
		$entityManager->flush() ;
		
		$response = new JsonResponse();
		// ...
		$response->setData(['data' => $gotit]);
		return $response;
    }
	
}
