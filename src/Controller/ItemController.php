<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Destination;
use App\Form\ItemType;
use App\Controller\DestinationController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

/**
 * @Route("/item")
 */
class ItemController extends AbstractController
{
	
    /**
     * @Route("/", name="item_index", methods={"GET"})
     */
    public function index(): Response
    {
        $items = $this->getDoctrine()
            ->getRepository(Item::class)
            ->findAll();

        return $this->render('item/index.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * @Route("/new", name="item_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_show", methods={"GET"})
     */
    public function show(Item $item): Response
    {
		$gotitem = false ;
		try {
			$user = $this->getUser();
			
			if ($item->getUsers()->contains($this->getUser()))
				$gotitem = true ;
			
		}
		catch (Exception $e) {
			$user = null ;
		}			
		
		return $this->render('item/show.html.twig', [
            'item' => $item,
			'user' => $user,
			'gotitem' => $gotitem
        ]);
    }
	
	public function destination_list(String $slug): Response
    {
		
		
		$repository = $this->getDoctrine()->getRepository(Destination::class);
		//$destination = $repository->findOneBy(['slug' => $slug]);
		$destination = $repository->findOneBySlug($slug);
		if (!$destination) {
			throw $this->createNotFoundException(
				'No product found for slug '.$slug
			);
		}
		
		//$itemmanager = new DestinationController() ;
		//$all_enfants = $itemmanager->get_all_items_under($destination);
		$all_enfants = array("yo"=>"1") ;
		
		$enfants = $repository->findBy(
			['parent' => $destination],
		);
		
        return $this->render('item/destination.html.twig', [
            'destination' => $destination,
			'enfants' => $enfants,
			'get_all_items_under' => $all_enfants
        ]);
    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Item $item): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_index', [
                'id' => $item->getId(),
            ]);
        }

        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Item $item): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_index');
    }
}
