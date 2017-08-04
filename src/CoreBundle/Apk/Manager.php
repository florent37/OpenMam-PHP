<?php

namespace CoreBundle\Apk;

use CoreBundle\Apk\FinderInterface;
use AppBundle\Model\Apk;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class Manager
{
    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(FinderInterface $finder, RouterInterface $router)
    {
        $this->finder = $finder;
        $this->router = $router;
    }

    /**
     * Retrieve all versions inside a directory by name.
     *
     * @param string $name
     *
     * @return array
     */
    public function getVersionsByName(string $name)
    {
        $versions = [];
        foreach ($this->finder->findAllByName($name) as $file) {
            $version = [];
            list($version['version_name'], $version['code']) = explode('/', $file->getRelativePath());
            $version['upload_date'] = (new \DateTime())
                ->setTimestamp($file->getMTime())
                ->format('Y-m-d H:i')
            ;
            $version['path'] = $this->router->generate('download_app', [
                'name' => $name,
                'version' => $version['version_name'],
                'code' => $version['code']
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $comment = dirname($file).'/comment.txt';
            if (file_exists($comment)) {
                $version['comment'] = file_get_contents($comment);
            }

            $versions[] = $version;
        }

        return $versions;
    }

    /**
     * Retrieve all apps in apk folder.
     *
     * @return array
     */
    public function getAll()
    {
        $apps = [];
        foreach ($this->finder->findInFirstLevel() as $file) {
            $app = [];
            $app['name'] = $file->getFilename();

            $lastVersionFolder =  $this->finder->getLastFolderByPath($file);
            $app['last_version'] = $lastVersionFolder->getFilename();

            $lastCodeFolder = $this->finder->getLastFolderByPath($lastVersionFolder);
            $app['last_code'] = $lastCodeFolder->getFilename();

            $app['last_uploaded_date'] = (new \DateTime())
                ->setTimestamp($lastCodeFolder->getMTime())
                ->format('Y-m-d H:i')
            ;

            $apps[] = $app;
        }

        return $apps;
    }

    /**
     * Save an apk.
     *
     * @param  Apk $apk
     */
    public function save(Apk $apk)
    {
        $path = $apk->getPath();
        $folder = dirname($path);
        @mkdir($folder, 0777, true);

        file_put_contents($path, $apk->getContent());
        if (null !== $comment = $apk->getComment()) {
            file_put_contents($folder.'/comment.txt', $comment);
        }
    }
}
