<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

class FormHandler
{
	private $validator;
	private $serializer;
	private $entityManager;

	public function __construct(ValidatorInterface $validator, SerializerInterface $serializer, EntityManagerInterface $entityManager)
	{
		$this->validator = $validator;
		$this->serializer = $serializer;
		$this->entityManager = $entityManager;
	}

	public function validate(Request $request, $object, $class, $callBack)
	{
		$object = $this->serializer->deserialize($request->getContent(), $class, 'json');
		$code = 401;
		$message = "Unauthorized";
		$errors = NULL;

		if (!is_null($object)) {
			$object->__construct();
			$violations = $this->validator->validate($object);

			if (count($violations) !== 0) {
				foreach ($violations as $violation) {
					$errors[$violation->getPropertyPath()] = $violation->getMessage();
				}
			}
			else {
				$callBack($object);

				$this->entityManager->persist($object);
				$this->entityManager->flush();

				$code = 201;
				$message = substr(strrchr($class, "\\"), 1).' created successfully';
			}
		}

		$response = new Response(json_encode([
			'code' => $code,
			'message' => $message,
			'errors' => $errors
		]), $code);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}
}
