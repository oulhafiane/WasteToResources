<?php

namespace App\Controller;

use App\Service\FormHandler;
use App\Entity\User;
use App\Entity\Picker;
use App\Entity\Reseller;
use App\Entity\Buyer;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController
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

	public function setPassword($user)
	{
		$user->setPassword(
			$this->encoder->encodePassword(
				$user,
				$user->getPassword()
			)
		);
	}

	/**
	 * @Route("/api/register", name="register_user", methods={"POST"})
	 */
	public function registerAction(Request $request)
	{
		return $this->form->validate($request, NULL, User::class, array($this, 'setPassword'));
	}
}
