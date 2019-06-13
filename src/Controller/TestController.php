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

	/**
	 * @Route("/api/test", name="test", methods={"GET"})
	 */
	public function testAction()
	{
		$current_user = $this->getCurrentUser();
		$response = new Response("Very good you are logged and you are a : ".get_class($current_user));

		return $response;
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
