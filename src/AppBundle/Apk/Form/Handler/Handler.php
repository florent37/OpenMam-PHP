<?php

namespace AppBundle\Apk\Form\Handler;

use CoreBundle\Apk\Manager;
use AppBundle\Model\Apk;
use Symfony\Component\Form\Form;

class Handler
{
    /**
     * @var ApkManager
     */
    private $manager;

    /**
     * @var string
     */
    private $apkFolder;

    public function __construct(Manager $manager, string $apkFolder)
    {
        $this->manager = $manager;
        $this->apkFolder = $apkFolder;
    }

    /**
     * Save an apk from a form.
     *
     * @param  Apk $apk
     */
    public function handle(Form $form)
    {
        $apk = $form->getData();

        $filePath = sprintf(
            '%s/%s/%s/%s/%s.apk',
            $this->apkFolder,
            $apk->getName(),
            $apk->getVersion(),
            $apk->getCode(),
            $apk->getName()
        );

        $apk = new Apk(
            $filePath,
            file_get_contents($apk->getFile()),
            $apk->getComment()
        );

        $this->manager->save($apk);
    }
}
