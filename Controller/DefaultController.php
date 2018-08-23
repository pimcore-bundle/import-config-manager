<?php

namespace ImportConfigManagerBundle\Controller;

use Pimcore\Controller\FrontendController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends FrontendController
{
    /**
     * @Route("/import_config_manager")
     */
    public function indexAction(Request $request)
    {
        return new Response('Hello world from import_config_manager');
    }
}
