<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Collecteur;
use App\Entity\GrossisteRevendeur;
use App\Entity\GrossisteAcheteur;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    private $encoder;
    private $validator;

    private function checkEmail($email)
    {
        if (is_null($email))
            return (False);
        $user = $this->getDoctrine()->getRepository(Collecteur::class)->findOneByEmail($email);
        if (!is_null($user))
            return (False);
        $user = $this->getDoctrine()->getRepository(GrossisteRevendeur::class)->findOneByEmail($email);
        if (!is_null($user))
            return (False);
        $user = $this->getDoctrine()->getRepository(GrossisteAcheteur::class)->findOneByEmail($email);
        if (!is_null($user))
            return (False);

        return (True);
    }

    private function nouveauUtilisateur(Request $request, Utilisateur $utilisateur): Response
    {
	$data = json_decode($request->getContent(), true);

	$code = 401;
	$message = 'Unauthorized';

	if (!is_null($data))
	{
	    $form = $this->createForm(RegistrationFormType::class, $utilisateur);
	    $form->submit($data);
	    $listErrors = $this->validator->validate($utilisateur);
	    if (count($listErrors) === 0 && $this->checkEmail($form->get('email')->getData()) === True) {
		$utilisateur->setPassword(
		    $this->encoder->encodePassword(
			$utilisateur,
			$form->get('password')->getData()
		    )
		);

		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($utilisateur);
		$entityManager->flush();

		$code = 201;
		$message = 'User created successfully';	
	    }
	}
	
	$response = new Response((json_encode([
		    'code' => $code,
		    'message' => $message
		])), $code);
	$response->headers->set('Content-Type', 'application/json');

	return $response;
    }

    /**
     * @Route("/api/collecteur", name="nouveau_collecteur", methods={"POST"})
     */
    public function nouveauCollecteur(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator): Response
    {
	$this->encoder = $encoder;
	$this->validator = $validator;
	$utilisateur = new Collecteur();
	
	return $this->nouveauUtilisateur($request, $utilisateur);
    }

    /**
     * @Route("/api/revendeur", name="nouveau_revendeur", methods={"POST"})
     */
    public function nouveauRevendeur(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator): Response
    {
	$this->encoder = $encoder;
	$this->validator = $validator;
	$utilisateur = new GrossisteRevendeur();

	return $this->nouveauUtilisateur($request, $utilisateur);
    }

    /**
     * @Route("/api/acheteur", name="nouveau_acheteur", methods={"POST"})
     */
    public function nouveauAcheteur(Request $request, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator): Response
    {
	$this->encoder = $encoder;
	$this->validator = $validator;
	$utilisateur = new GrossisteAcheteur();

	return $this->nouveauUtilisateur($request, $utilisateur);
    }
}
