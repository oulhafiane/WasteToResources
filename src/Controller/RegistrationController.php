<?php

namespace App\Controller;

use App\Service\FormHandler;
use App\Entity\User;
use App\Entity\Picker;
use App\Entity\Reseller;
use App\Entity\Buyer;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends AbstractController
{
	private $encoder;
	private $validator;
	private $form;

	public function __construct(UserPasswordEncoderInterface $encoder, ValidatorInterface $validator, FormHandler $form)
	{
		$this->encoder = $encoder;
		$this->validator = $validator;
		$this->form = $form;
	}

	public function setPassword($user, $form)
	{
		$user->setPassword(
			$this->encoder->encodePassword(
				$user,
				$form->get('password')->getData()
			)
		);
	}

	/**
	 * @Route("/api/public/picker", name="register_picker", methods={"POST"})
	 */
	public function pickerAction(Request $request)
	{
		$user = new Picker();

		return $this->form->validate($request, $user, RegistrationFormType::class, array($this, 'setPassword'));
	}

	/**
	 * @Route("/api/public/reseller", name="register_reseller", methods={"POST"})
	 */
	public function resellerAction(Request $request)
	{
		$user = new Reseller();

		return $this->form->validate($request, $user, RegistrationFormType::class, array($this, 'setPassword'));
	}

	/**
	 * @Route("/api/public/buyer", name="register_buyer", methods={"POST"})
	 */
	public function buyerAction(Request $request)
	{
		$user = new Buyer();

		return $this->form->validate($request, $user, RegistrationFormType::class, array($this, 'setPassword'));
	}
}
