<?php

namespace Bot\Controller;

use Botter\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class FrontController extends AbstractController
{
    public function indexAction(Request $request)
    {
        die(var_dump($request));
    }
}