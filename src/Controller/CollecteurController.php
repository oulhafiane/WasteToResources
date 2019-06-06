<?php

namespace App\Controller;

use App\Entity\Collecteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/collecteur_api")
 */
class CollecteurController extends AbstractController
{
    /**
     * @Route("/nouveau", name="nouveau_collecteur", methods={"POST"})
     */
    public function nouveau(Request $request): Response
    {
	$collecteur = new Collecteur();	
    }
}
