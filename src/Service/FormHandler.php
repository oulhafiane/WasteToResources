<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

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

	public function checkId(Request $request)
	{
		$data = json_decode($request->getContent(), true);
		if (null !== $data & !array_key_exists('id', $data))
			return true;
		else
			return false;
	}

	public function validate(Request $request, $object, $class, $callBack)
	{
		$code = 401;
		$message = "Unauthorized";
		$errors = NULL;

		try {
			$object = $this->serializer->deserialize($request->getContent(), $class, 'json');

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
		}catch (UniqueConstraintViolationException $ex) {
			$errors['email'] = 'This value is already used.';
		}catch (\LogicException $ex) {
			$errors['type'] = 'This value should not be blank.';
		}catch (\Exception $ex) {
			$code = 400;
			$message = $ex->getMessage();
		}

		return new JsonResponse([
			'code' => $code,
			'message' => $message,
			'errors' => $errors
		], $code);
	}
}
