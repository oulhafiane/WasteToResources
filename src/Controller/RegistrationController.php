<?php

namespace App\Controller;

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

	public function __construct(UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
	{
		$this->encoder = $encoder;
		$this->validator = $validator;
	}

	private function register(Request $request, User $user)
	{
		$data = json_decode($request->getContent(), true);
		$code = 401;
		$message = "Unauthorized";

		if (!is_null($data))
		{
			$form = $this->createForm(RegistrationFormType::class, $user);
			$form->submit($data);
			$violations = $this->validator->validate($user);
			$errors = NULL;

			if (count($violations) !== 0) {
				$errors = [];
				foreach ($violations as $violation) {
					$errors[$violation->getPropertyPath()] = $violation->getMessage();
				}
			}
			else {
				$user->setPassword(
					$this->encoder->encodePassword(
						$user,
						$form->get('password')->getData()
					)
				);

				$em = $this->getDoctrine()->getManager();
				$em->persist($user);
				$em->flush();

				$code = 201;
				$message = 'User created successfully';
			}
		}

		return $this->json([
			'code' => $code,
			'message' => $message,
			'errors' => $errors
		], $code);
	}

	/**
	 * @Route("/api/picker", name="register_picker", methods={"POST"})
	 */
	public function pickerAction(Request $request)
	{
		$picker = new Picker();

		return $this->register($request, $picker);
	}

	/**
	 * @Route("/api/reseller", name="register_reseller", methods={"POST"})
	 */
	public function resellerAction(Request $request)
	{
		$reseller = new Reseller();

		return $this->register($request, $reseller);
	}

	/**
	 * @Route("/api/buyer", name="register_buyer", methods={"POST"})
	 */
	public function buyerAction(Request $request)
	{
		$buyer = new Buyer();

		return $this->register($request, $buyer);
	}
}
