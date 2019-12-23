<?php

namespace App\Controller;

use App\Entity\Destination;
use App\Form\DestinationType;
use App\Service\DestinationManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;

/**
 * @Route("/destination")
 */
class DestinationController extends AbstractController
{
    /**
     * @Route("/", name="destination_index", methods={"GET"})
     */
    public function index(): Response
    {
        $destinations = $this->getDoctrine()
            ->getRepository(Destination::class)
            ->findAll();

        return $this->render('destination/index.html.twig', [
            'destinations' => $destinations,
        ]);
    }

    /**
     * @Route("/new", name="destination_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $destination = new Destination();
        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($destination);
            $entityManager->flush();

            return $this->redirectToRoute('destination_index');
        }

        return $this->render('destination/new.html.twig', [
            'destination' => $destination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="destination_list", methods={"GET"})
     */
    public function show(String $slug, PaginatorInterface $paginator, Request $request, DestinationManager $DestinationManager): Response
    {
		$repository = $this->getDoctrine()->getRepository(Destination::class);
		$destination = $repository->findOneBySlug($slug);
		if (!$destination) {
			throw $this->createNotFoundException(
				'Aucune destination trouvée pour '.$slug
			);
		}

		//
		$items_under = $DestinationManager->get_all_items_under($destination, 1) ;
        $destination_under = $DestinationManager->get_best_itemed_destinations_under($destination, 30) ;

        return $this->render('destination/destination_resume.html.twig', [
            'destination' => $destination,
            'children' => $DestinationManager->get_destinations_under($destination),
            'items_under' => $items_under,
            'dest_under' => $destination_under,
            'page' => 'resume'
        ]);
    }

    /**
     * @Route("/{slug}/destinations", name="destination_dest_under", methods={"GET"})
     */
    public function destination_dest_under(String $slug, PaginatorInterface $paginator, Request $request, DestinationManager $DestinationManager): Response
    {
        $repository = $this->getDoctrine()->getRepository(Destination::class);
        $destination = $repository->findOneBySlug($slug);
        if (!$destination) {
            throw $this->createNotFoundException(
                'Aucune destination trouvée pour '.$slug
            );
        }

        $children = $DestinationManager->get_all_destinations_under($destination, 1);
        if (!$children) {
            throw $this->createNotFoundException(
                'Aucune destination fille trouvée pour '.$slug
            );
        }

        $pagination = $paginator->paginate(
            $children, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );

        return $this->render('destination/destination_dest_under.html.twig', [
            'destination' => $destination,
            'children' => $DestinationManager->get_destinations_under($destination),
            'pagination' => $pagination,
            'page' => 'dest_under'
        ]);
    }

    /**
     * @Route("/{slug}/items", name="destination_items", methods={"GET"})
     */
    public function destination_items(String $slug, PaginatorInterface $paginator, Request $request, DestinationManager $DestinationManager): Response
    {
        $repository = $this->getDoctrine()->getRepository(Destination::class);
        $destination = $repository->findOneBySlug($slug);
        if (!$destination) {
            throw $this->createNotFoundException(
                'Aucune destination trouvée pour '.$slug
            );
        }

        $pagination = $paginator->paginate(
            $destination->getItems(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );

        return $this->render('destination/destination_items.html.twig', [
            'destination' => $destination,
            'children' => $DestinationManager->get_destinations_under($destination),
            'pagination' => $pagination,
            'page' => 'items'
        ]);
    }

    /**
     * @Route("/{slug}/all_items", name="destination_items_under", methods={"GET"})
     */
    public function destination_items_under(String $slug, PaginatorInterface $paginator, Request $request, DestinationManager $DestinationManager): Response
    {
        $repository = $this->getDoctrine()->getRepository(Destination::class);
        $destination = $repository->findOneBySlug($slug);
        if (!$destination) {
            throw $this->createNotFoundException(
                'Aucune destination trouvée pour '.$slug
            );
        }

        $pagination = $paginator->paginate(
            $DestinationManager->get_all_items_under($destination,1), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            20 /*limit per page*/
        );

        return $this->render('destination/destination_items_under.html.twig', [
            'destination' => $destination,
            'children' => $DestinationManager->get_destinations_under($destination),
            'pagination' => $pagination,
            'page' => 'items_under'
        ]);
    }
    /**
     * @Route("/{id}/edit", name="destination_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Destination $destination): Response
    {
        $form = $this->createForm(DestinationType::class, $destination);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('destination_index', [
                'id' => $destination->getId(),
            ]);
        }

        return $this->render('destination/edit.html.twig', [
            'destination' => $destination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="destination_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Destination $destination): Response
    {
        if ($this->isCsrfTokenValid('delete'.$destination->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($destination);
            $entityManager->flush();
        }

        return $this->redirectToRoute('destination_index');
    }
	
	public function get_all_items_under(Destination $destination): object
	{
		$items = $destination->getItems() ;
		$entityManager = $this->getDoctrine()->getManager();
		//$repository = $em->getRepository(Destination::class);
		
		//$this->getDoctrine();
		
		
		/*
		$children = $repository->findBy(['parent' => $destination]);
		if ($children)
			foreach ($children as $child)
				$items = array_merge($items, $child->get_all_items_under()) ;
			*/
		return $items;
	}
	
	
}
