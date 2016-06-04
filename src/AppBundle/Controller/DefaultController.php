<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render("AppBundle:default:index.html.twig");
    }

    /**
     * @Route("/load", name="load")
     */
    public function loadUrls(Request $request)
    {
        $urlsString = explode("\n", $request->get('urls'));

        foreach ($urlsString as $url) {
            
        }

        return new RedirectResponse($this->generateUrl('homepage'));
    }
}
