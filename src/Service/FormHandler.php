<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
		if (null !== $data && array_key_exists('id', $data))
			throw new HttpException(406, 'Field \'id\' not acceptable.');
	}

	public function validate(Request $request, $object, $class, $callBack)
	{
		$code = 401;
		$message = "Unauthorized";
		$extras = NULL;

		try {
			$object = $this->serializer->deserialize($request->getContent(), $class, 'json');

			if (!is_null($object)) {
				$object->__construct();
				$violations = $this->validator->validate($object);

				if (count($violations) !== 0) {
					foreach ($violations as $violation) {
						$extras[$violation->getPropertyPath()] = $violation->getMessage();
					}
				}
				else {
					$showId = $callBack($object);

					$this->entityManager->persist($object);
					$this->entityManager->flush();

					$code = 201;
					$message = substr(strrchr($class, "\\"), 1).' created successfully';
					if ($showId === True)
						$extras['id'] = $object->getId();
				}
			}
		}catch (UniqueConstraintViolationException $ex) {
			$extras['email'] = 'This value is already used.';
		}catch (\LogicException $ex) {
			$extras['type'] = 'This value should not be blank.';
		}catch (\Exception $ex) {
			$code = 400;
			$message = $ex->getMessage();
		}

		return new JsonResponse([
			'code' => $code,
			'message' => $message,
			'extras' => $extras
		], $code);
	}
}
