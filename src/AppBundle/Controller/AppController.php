<?php

namespace AppBundle\Controller;

use AppBundle\Apk\Finder;
use AppBundle\Apk\Manager as ApkManager;
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

/**
 * @Route("/apps")
 */
class AppController extends Controller
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
    public function uploadAction(Apk $apk)
    {
        try {
            $path = $apk->getPath();
            $folder = dirname($path);
            @mkdir($folder, 0777, true);

            file_put_contents($path, $apk->getContent());
            if (null !== $comment = $apk->getComment()) {
                file_put_contents($folder.'/comment.txt', $comment);
            }
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

        return (new BinaryFileResponse($filename))
            ->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $name.'.apk'
            )
        ;
    }
}
