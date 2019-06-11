<?php

namespace App\Controller;
use App\Entity\Collecteur;
use App\Entity\GrossisteRevendeur;
use App\Entity\GrossisteAcheteur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerInterface;

class TestController extends AbstractController
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
	$this->serializer = $serializer;
    }

    private function getCurrentUser()
    {

        if (!$this->has('security.token_storage')) {
            throw new \LogicException('The Security Bundle is not registered in your application.');
        }
        if (null === $token = $this->get('security.token_storage')->getToken()) {
            return;
        }
        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }
        return $user;
    }

    public function index()
    {
	$current_user = $this->getCurrentUser();
	$response = new Response("Very good you are logged and you are a : ".get_class($current_user));

	return $response;
    }

    public function collecteurs()
    {
	$collecteurs = $this->getDoctrine()->getRepository(Collecteur::class)->findAll();
	$data = $this->serializer->serialize($collecteurs, 'json');
	$response = new Response($data);
	$response->headers->set('Content-Type', 'application/json');

	return $response;
    }

    public function revendeurs()
    {
	$revendeurs = $this->getDoctrine()->getRepository(GrossisteRevendeur::class)->findAll();
	$data = $this->serializer->serialize($revendeurs, 'json');
	$response = new Response($data);
	$response->headers->set('Content-Type', 'application/json');

	return $response;
    }

    public function acheteurs()
    {
	$acheteurs = $this->getDoctrine()->getRepository(GrossisteAcheteur::class)->findAll();
	$data = $this->serializer->serialize($acheteurs, 'json');
	$response = new Response($data);
	$response->headers->set('Content-Type', 'application/json');

	return $response;
    }
}
