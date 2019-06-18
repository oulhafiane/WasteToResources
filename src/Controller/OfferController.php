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
use App\Helper\UploadedBase64EncodedFile;
use App\Helper\Base64EncodedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

class OfferController extends AbstractController
{
	private $serializer;
	private $validator;
	private $cr;
	private $params;

	public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, CurrentUser $cr, ParameterBagInterface $params)
	{
		$this->serializer = $serializer;
		$this->validator = $validator;
		$this->cr = $cr;
		$this->params = $params;
	}

	public function setOwner($offer)
	{
		$offer->setOwner($this->cr->getCurrentUser($this));
		$photos = $offer->getPhotos();
		if (null === $photos)
			return ;
		foreach($photos as $photo) {
			$file = new UploadedBase64EncodedFile(new Base64EncodedFile($photo->getFile()));
			$photo->setFile($file);
			$photo->setOffer($offer);
			$photo->setLink($this->params->get('uploads_base_url').'/'.$file->getClientOriginalName());
		}

		return True;
	}

	/**
	 * @Route("/api/offer/sale", name="create_sale_offer", methods={"POST"})
	 */
	public function createSaleOfferAction(Request $request, FormHandler $form)
	{
		$form->checkId($request);
		$user = $this->cr->getCurrentUser($this);
		if ($user instanceOf Picker)
		{
			$offer = new SaleOffer();
			return $form->validate($request, $offer, SaleOffer::class, array($this, 'setOwner'));
		}

		return $this->json([
			'code' => 401,
			'message' => 'Unauthorized',
			'extras' => ['Role' => 'You are not picker']
		], 401);
	}

	/**
	 * @Route("/api/offer/purchase", name="create_purchase_offer", methods={"POST"})
	 */
	public function createPurchaseOfferAction(Request $request, FormHandler $form)
	{
		$form->checkId($request);
		$user = $this->cr->getCurrentUser($this);
		if ($user instanceOf Reseller)
		{
			$offer = new PurchaseOffer();
			return $form->validate($request, $offer, PurchaseOffer::class, array($this, 'setOwner'));
		}

		return $this->json([
			'code' => 401,
			'message' => 'Unauthorized',
			'extras' => ['Role' => 'You are not reseller']
		], 401);
	}

	/**
	 * @Route("/api/offer/bulk_purchase", name="create_bulk_purchase_offer", methods={"POST"})
	 */
	public function createBulkPurchaseOfferAction(Request $request, FormHandler $form)
	{
		$form->checkId($request);
		$user = $this->cr->getCurrentUser($this);
		if ($user instanceOf Buyer)
		{
			$offer = new BulkPurchaseOffer();
			return $form->validate($request, $offer, BulkPurchaseOffer::class, array($this, 'setOwner'));
		}

		return $this->json([
			'code' => 401,
			'message' => 'Unauthorized',
			'extras' => ['Role' => 'You are not buyer']
		], 401);
	}

	/**
	 * @Route("/api/offer/auction", name="create_auction_offer", methods={"POST"})
	 */
	public function createAuctionOfferAction(Request $request, FormHandler $form)
	{
		$form->checkId($request);
		$user = $this->cr->getCurrentUser($this);
		if ($user instanceOf Reseller)
		{
			$offer = new AuctionBid();
			return $form->validate($request, $offer, AuctionBid::class, array($this, 'setOwner'));
		}

		return $this->json([
			'code' => 401,
			'message' => 'Unauthorized',
			'extras' => ['Role' => 'You are not reseller']
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
	 * @Route("/api/public/offers", name="list_offers", methods={"GET"})
	 */
	public function listOffersAction()
	{
		$offers = $this->getDoctrine()->getRepository(Offer::class)->findAll();
		$data = $this->serializer->serialize($offers, 'json', SerializationContext::create()->setGroups(array('offer')));
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
		$data = $this->serializer->serialize($offers, 'json', SerializationContext::create()->setGroups(array('offer')));
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
		$data = $this->serializer->serialize($offers, 'json', SerializationContext::create()->setGroups(array('offer')));
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
		$data = $this->serializer->serialize($offers, 'json', SerializationContext::create()->setGroups(array('offer')));
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
		$data = $this->serializer->serialize($offers, 'json', SerializationContext::create()->setGroups(array('offer')));
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
}
