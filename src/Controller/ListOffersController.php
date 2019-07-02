<?php

namespace App\Controller;

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
use Symfony\Component\HttpKernel\Exception\HttpException;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

class ListOffersController extends AbstractController
{
	private $serializer;
	private $em;

	public function __construct(SerializerInterface $serializer, EntityManagerInterface $em)
	{
		$this->serializer = $serializer;
		$this->em = $em;
	}

	private function listOffers($class, $request)
	{
		$page = $request->query->get('page', 1);
		$limit = $request->query->get('limit', 12);
		$category = $request->query->get('category', null);
		$results = $this->em->getRepository($class)->findOffer($page, $limit, $category)->getCurrentPageResults();
		$offers = array();
		foreach ($results as $result) {
			$offers[] = $result;
		}
		$data = $this->serializer->serialize($offers, 'json', SerializationContext::create()->setGroups(array('list-offers')));
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/api/categories", name="list_categories", methods={"GET"})
	 */
	public function listCategoriesAction()
	{
		$categories = $this->em->getRepository(Category::class)->findAll();
		$data = $this->serializer->serialize($categories, 'json', SerializationContext::create()->setGroups(array('list-offers')));
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/api/offers/{id}", name="specific_offer", methods={"GET"}, requirements={"id"="\d+"})
	 */
	public function SpecificOfferAction($id)
	{
		$offer = $this->em->getRepository(Offer::class)->find($id);
		if (null === $offer)
			throw new HttpException(404, "Offer not found.");
		$data = $this->serializer->serialize($offer, 'json', SerializationContext::create()->setGroups(array('list-offers', 'specific-offer')));
		$response = new Response($data);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	/**
	 * @Route("/api/offers", name="list_offers", methods={"GET"})
	 */
	public function listOffersAction(Request $request)
	{
		return $this->listOffers(Offer::class, $request);
	}

	/**
	 * @Route("/api/offers/sale", name="list_sale_offers", methods={"GET"})
	 */
	public function listSaleOffersAction(Request $request)
	{
		return $this->listOffers(SaleOffer::class, $request);
	}

	/**
	 * @Route("/api/offers/purchase", name="list_purchase_offers", methods={"GET"})
	 */
	public function listPurchaseOffersAction(Request $request)
	{
		return $this->listOffers(PurchaseOffer::class, $request);
	}

	/**
	 * @Route("/api/offers/bulk_purchase", name="list_bulk_purchase_offers", methods={"GET"})
	 */
	public function listBulkPurchaseOffersAction(Request $request)
	{
		return $this->listOffers(BulkPurchaseOffer::class, $request);
	}

	/**
	 * @Route("/api/offers/auction", name="list_auction_offers", methods={"GET"})
	 */
	public function listAuctionOffersAction(Request $request)
	{
		return $this->listOffers(AuctionBid::class, $request);
	}
}
