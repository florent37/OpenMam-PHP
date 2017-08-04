<?php

namespace AppBundle\Controller;

use AppBundle\Apk\Form\Handler\Handler;
use AppBundle\Apk\Form\Type\ApkType;
use CoreBundle\Apk\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends Controller
{
    /**
     * @Route("/", name="list_apps")
     */
    public function indexAction(Manager $manager)
    {
        return $this->render('apps/index.html.twig', [
            'apps' => $manager->getAll()
        ]);
    }

    /**
     * @Route("/add", name="add_apps")
     */
    public function addAction(Request $request, Handler $handler)
    {
        $form = $this->createForm(ApkType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($form);

                return $this->redirect($this->generateUrl('list_apps'));
            } catch (\Exception $e) {
            }
        }

        return $this->render('apps/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{name}", name="show_apps")
     */
    public function showAction(Manager $manager, string $name)
    {
        return $this->render('apps/show.html.twig', [
            'name' => $name,
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
