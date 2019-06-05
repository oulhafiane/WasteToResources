<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public function index()
    {
	return new Response('good work you are logged');
    }
}
