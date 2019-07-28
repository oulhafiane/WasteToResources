<?php

namespace App\Controller;

use App\Service\Helper;
use App\Service\CurrentUser;
use App\Entity\Notification;
use App\Entity\Message;
use App\Entity\Feedback;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\Picker;
use App\Entity\Reseller;
use App\Entity\Buyer;
use App\Entity\Offer;
use App\Entity\SaleOffer;
use App\Entity\PurchaseOffer;
use App\Entity\BulkPurchaseOffer;
use App\Entity\AuctionBid;
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
use Doctrine\ORM\Query\ResultSetMapping;

class CurrentUserController extends AbstractController
{
	private $cr;
	private $serializer;
	private $em;
	private $helper;

	public function __construct(CurrentUser $cr, SerializerInterface $serializer, EntityManagerInterface $em, Helper $helper)
	{
		$this->cr = $cr;
		$this->serializer = $serializer;
		$this->em = $em;
		$this->helper = $helper;
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

	private function getTransaction($id)
	{
		$current = $this->cr->getCurrentUser($this);
		$transaction = $this->em->getRepository(Transaction::class)->find($id);
		if ($current->getId() !== $transaction->getBuyer()->getId() && $current->getId() !== $transaction->getSeller()->getId())
			throw new HttpException(403, 'Forbidden.');

		return $transaction;
	}

	private function verifyTransaction($request, $transaction)
	{
		$data = json_decode($request->getContent(), true);

		if (null === $data)
			throw new HttpException(406, 'Not Acceptable.');

		if (!array_key_exists('transaction_id', $data)
			|| !array_key_exists('offer_id', $data)
			|| !array_key_exists('start_date', $data)
			|| !array_key_exists('key', $data))
			throw new HttpException(406, 'Not Acceptable.');

		if ($data['transaction_id'] !== $transaction->getId())
			throw new HttpException(406, 'Not Acceptable.');

		if ($data['offer_id'] !== $transaction->getOffer()->getId())
			throw new HttpException(406, 'Not Acceptable.');

		$date = new \DateTime($data['start_date']);
		$diff = $transaction->getStartDate()->diff($date);
		if (0 !== $diff->days || 0 !== $diff->invert || 0 !== $diff->y
			|| 0 !== $diff->m || 0 !== $diff->d || 0 !== $diff->h
			|| 0 !== $diff->h || 0 !== $diff->i || 0 !== $diff->s)
			throw new HttpException(406, 'Not Acceptable.');

		if ($data['key'] !== $transaction->getBuyerKey())
			throw new HttpException(406, 'Not Acceptable.');
	}

	/**
	 * @Route("/api/current/transactions/{id}/terminate", name="current_terminate_transaction", methods={"PATCH"}, requirements={"id"="\d+"})
	 */
	public function currentTerminateTransaction($id, Request $request)
	{
		$transaction = $this->getTransaction($id);
		$etat = $this->helper->getTransactionEtat($transaction);	

		if ($etat !== 1)
			throw new HttpException(406, 'This transaction cannot be terminated.');
		
		$buyer = $transaction->getBuyer();
		$seller = $transaction->getSeller();
		$current = $this->cr->getCurrentUser($this);

		if ($current->getId() !== $seller->getId())
			throw new HttpException(406, 'You are not the seller of this transaction.');

		$this->verifyTransaction($request, $transaction);

		$total = $transaction->getTotal();
		$seller->setBalance($seller->getBalance() + $total);
		$transaction->endTransaction();

		try {
			$this->em->persist($seller);
			$this->em->persist($transaction);
			$this->em->flush();
		} catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		return $this->json([
			'code' => 200,
			'message' => "Your transaction has been successfully completed."
		]);
	}

	/**
	 * @Route("/api/current/transactions/{id}/pay", name="current_pay_transaction", methods={"PATCH"}, requirements={"id"="\d+"})
	 */
	public function currentPayTransactionAction($id)
	{
		$transaction = $this->getTransaction($id);
		$etat = $this->helper->getTransactionEtat($transaction);	

		if ($etat !== 0)
			throw new HttpException(406, 'This transaction cannot be paid.');
		
		$buyer = $transaction->getBuyer();
		$seller = $transaction->getSeller();
		$current = $this->cr->getCurrentUser($this);

		if ($current->getId() !== $buyer->getId())
			throw new HttpException(406, 'You are not the buyer of this transaction.');

		$total = $transaction->getTotal();
		if ($buyer->getBalance() < $total)
			throw new HttpException(406, 'Insufficient balance.');

		$buyer->setBalance($buyer->getBalance() - $total);
		$transaction->setPaid();

		try {
			$this->em->persist($buyer);
			$this->em->persist($transaction);
			$this->em->flush();
		} catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}

		return $this->json([
			'code' => 200,
			'message' => "Your payment has been successfully processed."
		]);
	}

	/**
	 * @Route("/api/current/transactions/{id}", name="current_specific_transaction", methods={"GET"}, requirements={"id"="\d+"})
	 */
	public function currentSpecificTransactionAction($id)
	{
		$transaction = $this->getTransaction($id);
		$data = $this->serializer->serialize($transaction, 'json', SerializationContext::create()->setGroups(['transactions', "specific"]));
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/api/current/transactions/count", name="current_count_transactions", methods={"GET"})
	 */
	public function currentCountTransactionsAction()
	{
		$current = $this->cr->getCurrentUser($this);	

		$sql = "select count(*) as total from transaction where buyer_id = ? OR seller_id = ?";
		$stmt = $this->em->getConnection()->prepare($sql);
		$stmt->bindValue(1, $current->getId());
		$stmt->bindValue(2, $current->getId());
		$stmt->execute();
		$count = $stmt->fetch();

		return $this->json([
			'code' => 200,
			'message' => 'Your request was successfully submitted.',
			'extras' => ['count' => $count['total']]
		]);
	}

	/**
	 * @Route("/api/current/transactions", name="current_user_transactions", methods={"GET"})
	 */
	public function currentUserTransactionsAction(Request $request)
	{
		return $this->getResults($request , Transaction::class, ['transactions']);
	}

	/**
	 * @Route("/api/current/offers/count", name="current_user_offers_count", methods={"GET"})
	 */
	public function currentUserOffersCountAction(Request $request)
	{
		$current = $this->cr->getCurrentUser($this);	

		$type = $request->query->get('type', null);

		$sql = "select count(*) as total from offer where owner_id = ?";
		if (null !== $type)
			$sql .= " and type = ?";
		$stmt = $this->em->getConnection()->prepare($sql);
		$stmt->bindValue(1, $current->getId());
		if (null !== $type)
			$stmt->bindValue(2, $type);
		$stmt->execute();
		$count = $stmt->fetch();

		return $this->json([
			'code' => 200,
			'message' => 'Your request was successfully submitted.',
			'extras' => ['count' => $count['total']]
		]);
	}

	/**
	 * @Route("/api/current/offers", name="current_user_offers", methods={"GET"})
	 */
	public function currentUserOffersAction(Request $request)
	{
		$current = $this->cr->getCurrentUser($this);	

		if ($current instanceof Picker)
			return $this->getResults($request, SaleOffer::class, ['list-offers']);
		else if ($current instanceof Reseller) {
			$type = $request->query->get('type', 'auction');
			if ($type === 'purchase')
				return $this->getResults($request, PurchaseOffer::class, ['list-offers']);
			else
				return $this->getResults($request, AuctionBid::class, ['list-offers']);
		} else if ($current instanceof Buyer)
			return $this->getResults($request, BulkPurchaseOffer::class, ['list-offers']);

		throw new HttpException(500, "An error occured.");
	}

	/**
	 * @Route("/api/current/notifications", name="current_user_notifications", methods={"GET"})
	 */
	public function currentUserNotificationsAction(Request $request)
	{
		return $this->getResults($request, Notification::class, ['notifications']);
	}

	/**
	 * @Route("/api/current/messages/{email}", name="current_user_specific_messages", methods={"GET"})
	 */
	public function currentUserSpecificMessagesAction(Request $request, $email)
	{
		$current = $this->cr->getCurrentUser($this);	
		$page = $request->query->get('page', 1);
		$limit = $request->query->get('limit', 12);
		$user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
		if (null === $user)
			throw new HttpException(404, 'Email not found');
		$results = $this->em->getRepository(Message::class)->findByUser($user, $current, $page, $limit)->getCurrentPageResults();
		$objects = array();
		foreach ($results as $result) {
			array_unshift($objects, $result);
		}
		$data = $this->serializer->serialize($objects, 'json', SerializationContext::create()->setGroups(['messages']));
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/api/current/messages", name="current_user_messages", methods={"GET"})
	 */
	public function currentUserMessagesAction(Request $request)
	{
		$current = $this->cr->getCurrentUser($this);	
		
		$sql = "select count(*) as total, count(seen) - sum(seen) as count_not_seen, u.email as sender, (select m2.text from message m2 where m2.sender_id = m0.sender_id AND m2.receiver_id = m0.receiver_id ORDER BY m2.date DESC LIMIT 1) as last_message from message m0 join user u on m0.sender_id = u.id where m0.receiver_id = ? group by m0.sender_id";
		$stmt = $this->em->getConnection()->prepare($sql);
		$stmt->bindValue(1, $current->getId());
		$stmt->execute();
		$results = $stmt->fetchAll();

		$count_not_seen = $this->em->getRepository(Message::class)->getCountNotSeenByUser($current);

		$data = [
			'total_not_seen' => $count_not_seen,
			'messages' => $results
		];

		$data = $this->serializer->serialize($data, 'json');
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
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
