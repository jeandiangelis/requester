<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RequestStatus;
use AppBundle\Entity\Url;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

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

        /** @var Url $lastBatchUrl */
        $lastBatchUrl = $doctrine
            ->getRepository(Url::class)
            ->getLastBatch()
        ;

        $nextBatch = 1;

        if ($lastBatchUrl) {
            $nextBatch = $lastBatchUrl->getBatch() + 1;
        }

        $entities = [];
        foreach ($urls as $url) {
            $entities[$url] = $entity = new Url($url, $nextBatch, -1);
            $doctrine->getEntityManager()->persist($entity);
        }

        $doctrine->getEntityManager()->flush();

        $client = new Client();
        /** @var Url $entity */
        foreach ($entities as $entity) {
            $promise = $client->getAsync($entity->getName());

            $promise->then(
                function (ResponseInterface $res) use ($entity) {
                    $entity->setStatus($res->getStatusCode());
                },
                function (RequestException $e) use ($entity) {
                    $entity->setStatus($e->getCode());
                }
            );
        }



        return new Response();
    }
}
