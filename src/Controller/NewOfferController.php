<?php

namespace App\Controller;

use App\Service\CurrentUser;
use App\Service\FormHandler;
use App\Entity\Offer;
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

	public function __construct(ValidatorInterface $validator, CurrentUser $cr, CacheManager $cacheManager, FormHandler $form)
	{
		$this->validator = $validator;
		$this->cr = $cr;
		$this->imagineCacheManager = $cacheManager;
		$this->form = $form;
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
