<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Item;
use Knp\Component\Pager\PaginatorInterface;
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
    /**
     * @Route("/user/{slug}", name="user_profile", methods={"GET"})
     */
    public function show(String $slug, PaginatorInterface $paginator, Request $request): Response
    {
		$repository = $this->getDoctrine()->getRepository(User::class);
		$user = $repository->findOneBy(['slug' => $slug]);
		if (!$user) {
			throw $this->createNotFoundException(
				'Utilisateur inconnu pour '.$slug
			);
		}

        $items = $paginator->paginate(
            $user->getItems(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            16 /*limit per page*/
        );

		return $this->render('user/show.html.twig', [
            'utilisateur' => $user,
            'items' => $items,
            'itemsdirect' => $user->getItems()
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
