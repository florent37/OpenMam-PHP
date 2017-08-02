<?php

namespace CoreBundle\ParamConverters;

use AppBundle\Model\Apk;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApkConverter implements ParamConverterInterface
{
    /**
     * @var string
     */
    private $apkFolder;

    public function __construct(string $apkFolder)
    {
        $this->apkFolder = $apkFolder;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        $resolver = new OptionsResolver();
        $required = ['apk-name', 'apk-version', 'apk-code', 'apk-comment'];
        $resolver
            ->setDefaults(['apk-comment' => null])
            ->setRequired($required)
        ;
        $headers = $request->headers;
        $resolved = [];
        foreach ($required as $value) {
            if (!$headers->has($value)) {
                continue;
            }
            $resolved[$value] = $headers->get($value);
        }

        $resolver->resolve($resolved);

        $filePath = sprintf(
            '%s/%s/%s/%s/%s.apk',
            $this->apkFolder,
            $resolved['apk-name'],
            $resolved['apk-version'],
            $resolved['apk-code'],
            $resolved['apk-name']
        );

        $apk = new Apk(
            $filePath,
            $request->getContent(),
            $resolved['apk-comment'] ?? null
        );

        $request->attributes->set($configuration->getName(), $apk);
    }

    public function supports(ParamConverter $configuration)
    {
        if (Apk::class !== $configuration->getClass()) {
            return false;
        }

        return true;
    }
}
