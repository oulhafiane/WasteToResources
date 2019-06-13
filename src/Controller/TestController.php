<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Picker;
use App\Entity\SaleOffer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerInterface;

class TestController extends AbstractController
{
	private $serializer;

	public function __construct(SerializerInterface $serializer)
	{
		$this->serializer = $serializer;
	}

	/**
	 * @Route("/users", name="users", methods={"GET"})
	 */
	public function usersAction()
	{
		$users = $this->getDoctrine()->getRepository(User::class)->findAll();
		$data = $this->serializer->serialize($users, 'json');
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
}
