<?php

namespace App\Controller;

use App\Entity\Collecteur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/collecteur_api")
 */
class CollecteurController extends Controller
{
    /**
     * @Route("/nouveau", name="nouveau_collecteur", methods={"POST"})
     */
    public function nouveau(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
	$data = json_decode($request->getContent(), true);
	$collecteur = new Collecteur();
	$collecteur->setEmail($data['email']);
	$collecteur->setPassword($encoder->encodePassword($collecteur, $data['password']));
	$collecteur->setPrenom($data['prenom']);
	$collecteur->setNom($data['nom']);
	$collecteur->setVille($data['ville']);
	$collecteur->setAddresse($data['addresse']);
	$collecteur->setPays($data['pays']);
	$collecteur->setTelephone($data['telephone']);
	$em = $this->getDoctrine()->getManager();
	$em->persist($collecteur);
	$em->flush();
	$response = new Response();
	$response->setContent(json_encode([
		'code' => 201, 
		'message' => 'User created successfully'
		]));
	$response->headers->set('Content-Type', 'application/json');
	return $response;
    }
}
