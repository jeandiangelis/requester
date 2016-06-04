<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Url;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/api/urls", name="urls")
     * @Method(methods={"GET"})
     */
    public function getUrls(Request $request)
    {
        $data = $this
            ->get('doctrine')
            ->getRepository(Url::class)
            ->findAll()
        ;
        $a = $this->get('serializer')->serialize($data, 'JSON');

        dump($a);exit;
        return new JsonResponse($data);
    }

    /**
     * @Route("/api/urls", name="urls_post")
     * @Method(methods={"POST"})
     */
    public function saveUrls(Request $request)
    {
        dump($request);exit;
        return new JsonResponse($data);
    }
}
