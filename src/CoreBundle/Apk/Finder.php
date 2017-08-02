<?php

namespace CoreBundle\Apk;

use CoreBundle\Apk\FinderInterface;
use Symfony\Component\Finder\Finder as FinderComponent;
use Symfony\Component\Finder\SplFileInfo;

class Finder implements FinderInterface
{
    /**
     * @var string
     */
    private $apkFolder;

    public function __construct(string $apkFolder)
    {
        $this->apkFolder = $apkFolder;
    }

    /**
     * Retrieve folders in first level.
     *
     * @return Finder
     */
    public function findInFirstLevel()
    {
        $finder = new FinderComponent();
        $finder->directories()->in($this->apkFolder)->sortByName();
        $finder->depth('== 0');

        return $finder;
    }

    /**
     * Retrieve the last folder by name in a directory.
     *
     * @param  SplFileInfo $file
     *
     * @return string
     */
    public function getLastFolderByPath(SplFileInfo $file)
    {
        $finder = new FinderComponent();

        $finder
            ->directories()
            ->in($file->getPathname())
            ->sortByName()
            ->depth('== 0')
        ;

        $iterator = $finder->getIterator();

        return end($iterator);
    }

    /**
     * Retrieve all apk in a folder by name.
     *
     * @param  string $name
     *
     * @return Finder
     */
    public function findAllByName(string $name)
    {
        return (new FinderComponent())
            ->files()
            ->in($this->apkFolder.'/'.$name)
            ->name('*.apk')
            ->sortByName()
        ;
    }
}
