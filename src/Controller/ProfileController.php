<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Feedback;
use App\Service\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class ProfileController extends AbstractController
{
	private $cr;
	private $em;
	private $serializer;

	public function __construct(CurrentUser $cr, EntityManagerInterface $em, SerializerInterface $serializer)
	{
		$this->cr = $cr;
		$this->em = $em;
		$this->serializer = $serializer;
	}

	private function sendMessage($email, $data, $class)
	{
		$receiver = $this->em->getRepository(User::class)->findOneBy([
			'email' => $email
		]);

		if (null === $receiver)
			throw new HttpException(404, 'Not Found.');

		if (!array_key_exists('message', $data))
			throw new HttpException(406, 'Not Acceptable.');

		if (null !== $data['message']) {
			$sender = $this->cr->getCurrentUser($this);
			$message = new $class();
			$message->setSender($sender);
			$message->setReceiver($receiver);
			$message->setText($data['message']);

			if ($message instanceof Feedback) {
				$message->setRate($data['rate']);	
			}

			try {
				$this->em->persist($message);
				$this->em->flush();
			} catch (\Exception $ex) {
				throw new HttpException(406, 'Not Acceptable.');
			}
		}

	}

    /**
     * @Route("/api/profiles/{email}/message", name="send_message_to_profile", methods={"POST"})
     */
    public function sendMessageAction($email, Request $request)
    {
		$data = json_decode($request->getContent(), true);

		$this->sendMessage($email, $data, Message::class);

		return $this->json([
			'code' => 200,
			'message' => 'Message sent successfully.'
		]);
    }

    /**
     * @Route("/api/profiles/{email}/feedback", name="send_feedback_to_profile", methods={"POST"})
     */
    public function sendFeedbackAction($email, Request $request)
    {
		$data = json_decode($request->getContent(), true);

		if (!array_key_exists('rate', $data))
			throw new HttpException(406, 'Not Acceptable.');

		$this->sendMessage($email, $data, Feedback::class);

		return $this->json([
			'code' => 200,
			'message' => 'Feedback sent successfully.'
		]);
    }

    /**
     * @Route("/api/profiles/{email}", name="get_profile", methods={"GET"})
     */
    public function getProfileAction($email)
    {
		$profile = $this->em->getRepository(User::class)->findOneBy([
			'email' => $email
		]);

		if (null === $profile)
			throw new HttpException(404, 'Not Found.');

		$data = $this->serializer->serialize($profile, 'json', SerializationContext::create()->setGroups(array('profile')));
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
    }
}
