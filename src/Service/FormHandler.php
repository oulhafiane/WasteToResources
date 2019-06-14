<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormHandler extends AbstractController
{
	private $validator;

	public function __construct(ValidatorInterface $validator)
	{
		$this->validator = $validator;
	}

	public function validate(Request $request, $object, $class, $callBack)
	{
		$data = json_decode($request->getContent(), true);
		$code = 401;
		$message = "Unauthorized";
		$errors = NULL;

		if (!is_null($data)) {
			$form = $this->createForm($class, $object);
			$form->submit($data);
			$violations = $this->validator->validate($object);

			if (count($violations) !== 0) {
				$erros = [];
				foreach ($violations as $violation) {
					$errors[$violation->getPropertyPath()] = $violation->getMessage();
				}
			}
			else {
				$callBack($object, $form);

				$em = $this->getDoctrine()->getManager();
				$em->persist($object);
				$em->flush();

				$code = 201;
				$message = substr(strrchr(get_class($object), "\\"), 1).' created successfully';
			}
		}

		return $this->json([
			'code' => $code,
			'message' => $message,
			'errors' => $errors
		], $code);
	}
}
