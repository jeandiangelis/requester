<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RequestStatus;
use AppBundle\Entity\Url;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $json = $this->get('jms_serializer')->serialize($data, 'json');

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/api/urls", name="urls_post")
     * @Method(methods={"POST"})
     */
    public function saveUrls(Request $request)
    {
        $urls = explode("\n", $request->get('urls'));

        $doctrine = $this->getDoctrine();

        $status = $doctrine
            ->getRepository(RequestStatus::class)
            ->findOneBy(['code' => RequestStatus::LOADING]);

        /** @var Url $lastBatchUrl */
        $lastBatchUrl = $doctrine
            ->getRepository(Url::class)
            ->getLastBatch()
        ;

        $nextBatch = 1;

        if ($lastBatchUrl) {
            $nextBatch = $lastBatchUrl->getBatch() + 1;
        }

        foreach ($urls as $url) {
            $entity = new Url($url, $nextBatch, $status);
            $doctrine->getEntityManager()->persist($entity);
        }

        $doctrine->getEntityManager()->flush();
        
        return new Response();
    }
}
