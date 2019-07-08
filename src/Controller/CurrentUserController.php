<?php

namespace App\Controller;

use App\Service\CurrentUser;
use App\Entity\Notification;
use App\Entity\Message;
use App\Entity\Feedback;
use App\Entity\Transaction;
use App\Entity\Picker;
use App\Entity\Reseller;
use App\Entity\Buyer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class CurrentUserController extends AbstractController
{
	private $cr;
	private $serializer;
	private $em;

	public function __construct(CurrentUser $cr, SerializerInterface $serializer, EntityManagerInterface $em)
	{
		$this->cr = $cr;
		$this->serializer = $serializer;
		$this->em = $em;
	}
	
	private function generateJWT()
	{
		$user = $this->cr->getCurrentUser($this);	
		$token = (new Builder())
			->set('mercure', ['subscribe' => ['waste_to_resources/user/'.$user->getEmail()]])
			->sign(new Sha256(), $this->getParameter('mercure_secret_key'))
			->getToken();

		return $token;
	}

	private function getResults($request, $class, $groups)
	{
		$current = $this->cr->getCurrentUser($this);	
		$page = $request->query->get('page', 1);
		$limit = $request->query->get('limit', 12);
		$results = $this->em->getRepository($class)->findByUser($current, $page, $limit)->getCurrentPageResults();
		$objects = array();
		foreach ($results as $result) {
			$objects[] = $result;
		}
		$data = $this->serializer->serialize($objects, 'json', SerializationContext::create()->setGroups($groups));
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/api/current/transactions/{id}", name="current_specific_transaction", methods={"GET"}, requirements={"id"="\d+"})
	 */
	public function currentSpecificTransactionAction($id)
	{
		$current = $this->cr->getCurrentUser($this);
		$transaction = $this->em->getRepository(Transaction::class)->find($id);
		if ($current->getId() === $transaction->getBuyer()->getId() || $current->getId() === $transaction->getSeller()->getId()) {
			$data = $this->serializer->serialize($transaction, 'json', SerializationContext::create()->setGroups(['transactions', "specific"]));
		} else
			throw new HttpException(403, 'Forbidden.');
		
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/api/current/transactions", name="current_user_transactions", methods={"GET"})
	 */
	public function currentUserTransactionsAction(Request $request)
	{
		return $this->getResults($request , Transaction::class, ['transactions']);
	}

	/**
	 * @Route("/api/current/notifications", name="current_user_notifications", methods={"GET"})
	 */
	public function currentUserNotificationsAction(Request $request)
	{
		return $this->getResults($request, Notification::class, ['notifications']);
	}

	/**
	 * @Route("/api/current/messages", name="current_user_messages", methods={"GET"})
	 */
	public function currentUserMessagesAction(Request $request)
	{
		return $this->getResults($request, Message::class, ['messages']);
	}

	/**
	 * @Route("/api/current/feedbacks", name="current_user_feedbacks", methods={"GET"})
	 */
	public function currentUserFeedbacksAction(Request $request)
	{
		return $this->getResults($request, Feedback::class, ['feedbacks']);
	}

	private function setSeen($class, $findBy, $message)
	{
		$objects = $this->em->getRepository($class)->findBy($findBy);
		foreach ($objects as $object) {
			if ($object->getSeen() === false) {
				$object->setSeen();
				$this->em->persist($object);
			}
		}
		try {
			$this->em->flush();
			return $this->json([
				'code' => 200,
				'message' => $message
			], 200);
		} catch (\Exception $ex) {
			return $this->json([
				'code' => 406,
				'message' => 'Not Acceptable.'
			], 200);
		}
	}

	/**
	 * @Route("/api/current/messages/seen", name="messages_seen", methods={"PATCH"})
	 */
	public function seenMessagesAction()
	{
		$current = $this->cr->getCurrentUser($this);	

		return $this->setSeen(Message::class, [
			'receiver' => $current,
			'seen' => false
		], 'Messages updated successfully.');
	}

	/**
	 * @Route("/api/current/notifications/seen", name="notifications_seen", methods={"PATCH"})
	 */
	public function seenNotificationAction()
	{
		$current = $this->cr->getCurrentUser($this);	

		return $this->setSeen(Notification::class, [
			'user' => $current,
			'seen' => false
		], 'Notifications updated successfully.');
	}

    /**
     * @Route("/api/current/infos", name="current_user_infos", methods={"GET"})
     */
    public function currentUserInfosAction()
    {
		$current = $this->cr->getCurrentUser($this);	
		$data = $this->serializer->serialize($current, 'json', SerializationContext::create()->setGroups(array('infos')));
		$data = json_decode($data, true);
		$data['mercure_token'] = sprintf("%s", $this->generateJWT());
		$data = json_encode($data);
		$response = new Response($data, 200);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
    }
}
