<?php

namespace App\Controller;

use App\Service\Helper;
use App\Service\CurrentUser;
use App\Service\FormHandler;
use App\Entity\Offer;
use App\Entity\SaleOffer;
use App\Entity\PurchaseOffer;
use App\Entity\BulkPurchaseOffer;
use App\Entity\AuctionBid;
use App\Entity\OnHold;
use App\Helper\UploadedBase64EncodedFile;
use App\Helper\Base64EncodedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class NewOfferController extends AbstractController
{
	private $validator;
	private $cr;
	private $imagineCacheManager;
	private $form;
	private $helper;

	public function __construct
	(
		ValidatorInterface $validator,
		CurrentUser $cr,
		CacheManager $cacheManager,
		FormHandler $form,
		Helper $helper
	)
	{
		$this->validator = $validator;
		$this->cr = $cr;
		$this->imagineCacheManager = $cacheManager;
		$this->form = $form;
		$this->helper = $helper;
	}

	private function payFees($user, $offer, $em)
	{
		$total = $offer->getPrice() * $offer->getWeight();
		$fees = null;
		if ($offer instanceof PurchaseOffer) {
			$fees = $this->helper->getOfferFees($total, 'feesPurchaseOfferStatic', 'feesPurchaseOfferDynamic');
		} else if ($offer instanceof BulkPurchaseOffer) {
			$fees = $this->helper->getOfferFees($total, 'feesBulkPurchaseOfferStatic', 'feesBulkPurchaseOfferDynamic');
		} else if ($offer instanceof AuctionBid) {
			$fees = $this->helper->getOfferFees($total, 'feesAuctionBidStatic', 'feesAuctionBidDynamic');
		}

		if (null === $fees)
			return;

		if ($user->getBalance() < ($total + $fees))
			throw new HttpException(406, 'Insufficient balance.');
		$user->setBalance($user->getBalance() - $fees);

		$onHold = new OnHold();
		$onHold->setOffer($offer);
		$onHold->setUser($user);
		$onHold->setFees($fees);

		try {
			$em->persist($onHold);
			$em->persist($user);
		} catch (\Exception $ex) {
			throw new HttpException(406, 'Not Acceptable.');
		}
	}

	public function setOwner($offer, $em)
	{
		$currentUser = $this->cr->getCurrentUser($this);
		$this->payFees($currentUser, $offer, $em);
		$offer->setOwner($currentUser);

		$photos = $offer->getPhotos();
		if (null === $photos)
			return True;
		foreach($photos as $photo) {
			$file = new UploadedBase64EncodedFile(new Base64EncodedFile($photo->getFile()));
			$photo->setFile($file);
			$photo->setOffer($offer);
			$photo->setLink($file->getClientOriginalName());
			$this->imagineCacheManager->getBrowserPath($photo->getLink(), 'photo_thumb');
			$this->imagineCacheManager->getBrowserPath($photo->getLink(), 'photo_scale_down');
		}

		$violations = $this->validator->validate($offer, null, ['new-photo']);
		$message = '';
		foreach ($violations as $violation) {
			$message .= $violation->getPropertyPath().': '.$violation->getMessage().' ';
		}
		if (count($violations) !== 0)
			throw new HttpException(406, $message);

		return True;
	}

	private function checkRoleAndId(Request $request)
	{
		$data = json_decode($request->getContent(), true);
		if (null !== $data && array_key_exists('id', $data))
			throw $this->createAccessDeniedException();
		if (null !== $data && array_key_exists('type', $data))
		{
			switch ($data['type'])
			{
			case 'sale':
				$this->denyAccessUnlessGranted('ROLE_PICKER');
				return ;
			case 'purchase':
				$this->denyAccessUnlessGranted('ROLE_RESELLER');
				return ;
			case 'bulk_purchase':
				$this->denyAccessUnlessGranted('ROLE_BUYER');
				return ;
			case 'auction':
				$this->denyAccessUnlessGranted('ROLE_RESELLER');
				return ;
			default:
				throw $this->createAccessDeniedException();
				break;
			}
		}
		throw $this->createAccessDeniedException();
	}

	/**
	 * @Route("/api/offers", name="create_offer", methods={"POST"})
	 */
	public function createOfferAction(Request $request)
	{
		$this->checkRoleAndId($request);

		return $this->form->validate($request, Offer::class, array($this, 'setOwner'), ['new-offer'], ['new-offer']);
	}
}
