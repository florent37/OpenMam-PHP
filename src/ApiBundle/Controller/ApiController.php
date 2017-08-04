<?php

namespace ApiBundle\Controller;

use CoreBundle\Apk\Finder;
use CoreBundle\Apk\Manager as ApkManager;
use AppBundle\Model\Apk;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiController extends Controller
{
    /**
     * @Route("/")
     */
    public function listAction(ApkManager $manager)
    {
        return new JsonResponse(['apps' => $manager->getAll()]);
    }

    /**
     * @Route("/upload", requirements={
     *    "name": "\w+",
     *    "version": "\w+",
     *    "code": "\d+"
     * })
     * @Method({"POST"})
     */
    public function uploadAction(Apk $apk, ApkManager $manager)
    {
        try {
            $manager->save($apk);
        } catch (\Throwable $e) {
            return new JsonResponse(['message' => $e->getMessage()]);
        }

        return new JsonResponse(['message' => 'ok']);
    }

    /**
     * @Route("/{name}")
     */
    public function showAction(ApkManager $manager, string $name)
    {
        return new JsonResponse([
            'versions' => $manager->getVersionsByName($name)
        ]);
    }

    /**
     * @Route("/{name}/{version}/{code}", name="download_app")
     */
    public function downloadAction(string $name, string $version, string $code)
    {
        $filename = sprintf(
            '%s/data/apk/%s/%s/%s/%s.apk',
            $this->getParameter('kernel.project_dir'),
            $name,
            $version,
            $code,
            $name
        );

        $response = (new BinaryFileResponse($filename))
            ->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $name.'.apk'
            )
        ;

        $response->headers->set('Content-Type', 'application/vnd.android.package-archive');

        return $response;
    }
}
