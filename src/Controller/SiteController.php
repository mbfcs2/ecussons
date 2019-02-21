<?php

namespace App\Controller;

use App\Entity\Destination;

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

}
