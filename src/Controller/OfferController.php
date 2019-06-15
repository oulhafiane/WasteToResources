<?php

namespace App\Controller;

use App\Service\CurrentUser;
use App\Service\FormHandler;
use App\Entity\User;
use App\Entity\Picker;
use App\Entity\Reseller;
use App\Entity\Buyer;
use App\Entity\Category;
use App\Entity\Offer;
use App\Entity\SaleOffer;
use App\Entity\PurchaseOffer;
use App\Entity\BulkPurchaseOffer;
use App\Entity\AuctionBid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

class OfferController extends AbstractController
{
	private $serializer;
	private $validator;
	private $cr;

	public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, CurrentUser $cr)
	{
		$this->serializer = $serializer;
		$this->validator = $validator;
		$this->cr = $cr;
	}

	public function setOwner($offer)
	{
		$offer->setOwner($this->cr->getCurrentUser($this));
	}

	/**
	 * @Route("/api/offer/sale", name="create_sale_offer", methods={"POST"})
	 */
	public function createSaleOfferAction(Request $request, FormHandler $form)
	{
		$user = $this->cr->getCurrentUser($this);
		if ($user instanceOf Picker)
		{
			$offer = new SaleOffer();
			return $form->validate($request, $offer, SaleOffer::class, array($this, 'setOwner'));
		}

		return $this->json([
			'code' => 401,
			'message' => 'Unauthorized',
			'errors' => ['Role' => 'You are not picker']
		], 401);
	}

	/**
	 * @Route("/api/offer/purchase", name="create_purchase_offer", methods={"POST"})
	 */
	public function createPurchaseOfferAction(Request $request, FormHandler $form)
	{
		$user = $this->cr->getCurrentUser($this);
		if ($user instanceOf Reseller)
		{
			$offer = new PurchaseOffer();
			return $form->validate($request, $offer, PurchaseOffer::class, array($this, 'setOwner'));
		}

		return $this->json([
			'code' => 401,
			'message' => 'Unauthorized',
			'errors' => ['Role' => 'You are not reseller']
		], 401);
	}

	/**
	 * @Route("/api/offer/bulk_purchase", name="create_bulk_purchase_offer", methods={"POST"})
	 */
	public function createBulkPurchaseOfferAction(Request $request, FormHandler $form)
	{
		$user = $this->cr->getCurrentUser($this);
		if ($user instanceOf Buyer)
		{
			$offer = new BulkPurchaseOffer();
			return $form->validate($request, $offer, BulkPurchaseOffer::class, array($this, 'setOwner'));
		}

		return $this->json([
			'code' => 401,
			'message' => 'Unauthorized',
			'errors' => ['Role' => 'You are not buyer']
		], 401);
	}

	/**
	 * @Route("/api/offer/auction", name="create_auction_offer", methods={"POST"})
	 */
	public function createAuctionOfferAction(Request $request, FormHandler $form)
	{
		$user = $this->cr->getCurrentUser($this);
		if ($user instanceOf Reseller)
		{
			$offer = new AuctionBid();
			return $form->validate($request, $offer, AuctionBid::class, array($this, 'setOwner'));
		}

		return $this->json([
			'code' => 401,
			'message' => 'Unauthorized',
			'errors' => ['Role' => 'You are not reseller']
		], 401);
	}

	/**
	 * @Route("/api/public/categories", name="list_categories", methods={"GET"})
	 */
	public function listCategoriesAction()
	{
		$categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
		$data = $this->serializer->serialize($categories, 'json');
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/api/public/offers/sale", name="list_sale_offers", methods={"GET"})
	 */
	public function listSaleOffersAction()
	{
		$offers = $this->getDoctrine()->getRepository(SaleOffer::class)->findAll();
		$data = $this->serializer->serialize($offers, 'json');
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/api/public/offers/purchase", name="list_purchase_offers", methods={"GET"})
	 */
	public function listPurchaseOffersAction()
	{
		$offers = $this->getDoctrine()->getRepository(PurchaseOffer::class)->findAll();
		$data = $this->serializer->serialize($offers, 'json');
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/api/public/offers/bulk_purchase", name="list_bulk_purchase_offers", methods={"GET"})
	 */
	public function listBulkPurchaseOffersAction()
	{
		$offers = $this->getDoctrine()->getRepository(BulkPurchaseOffer::class)->findAll();
		$data = $this->serializer->serialize($offers, 'json');
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/api/public/offers/auction", name="list_auction_offers", methods={"GET"})
	 */
	public function listAuctionOffersAction()
	{
		$offers = $this->getDoctrine()->getRepository(AuctionBid::class)->findAll();
		$data = $this->serializer->serialize($offers, 'json');
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
}
