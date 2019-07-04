<?php

namespace App\Controller;

use App\Service\CurrentUser;
use App\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Doctrine\ORM\EntityManagerInterface;

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

	/**
	 * @Route("/api/notifications/seen", name="notifications_seen", methods={"POST"})
	 */
	public function seenNotificationAction(Request $request)
	{
		$current = $this->cr->getCurrentUser($this);	
		$data = json_decode($request->getContent(), true);
		foreach ($data['notifications'] as $notification_id) {
			$notification = $this->em->getRepository(Notification::class)->find($notification_id);
			if ($notification->getSeen() === false) {
				$notification->setSeen();
				$this->em->persist($notification);
			}
		}
		try {
			$this->em->flush();
			return $this->json([
				'code' => 200,
				'message' => 'Notifications updated successfully.'
			], 200);
		} catch (\Exception $ex) {
			return $this->json([
				'code' => 406,
				'message' => 'Not Acceptable.'
			], 200);
		}
	}

	/**
	 * @Route("/api/current/notifications", name="current_user_notifications", methods={"GET"})
	 */
	public function currentUserNotificationsActions(Request $request)
	{
		$page = $request->query->get('page', 1);
		$limit = $request->query->get('limit', 12);
		$results = $this->em->getRepository(Notification::class)->findNotifications($page, $limit)->getCurrentPageResults();
		$notifications = array();
		foreach ($results as $result) {
			$notifications[] = $result;
		}
		$data = $this->serializer->serialize($notifications, 'json', SerializationContext::create()->setGroups(array('notifications')));
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

    /**
     * @Route("/api/current/infos", name="current_user_infos", methods={"GET"})
     */
    public function currentUserInfosAction()
    {
		$current = $this->cr->getCurrentUser($this);	
		$data = $this->serializer->serialize($current, 'json', SerializationContext::create()->setGroups(array('infos')));
		$response = new Response($data, 200);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
    }
}
