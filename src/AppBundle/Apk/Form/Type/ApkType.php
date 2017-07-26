<?php

namespace AppBundle\Apk\Form\Type;

use AppBundle\Apk\Form\Model\Apk;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApkType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'attr' => [
                    'placeholder' => 'App Market'
                ]
            ])
            ->add('version', null, [
                'label' => 'Version (integer.integer.integer)',
                'attr' => [
                    'placeholder' => 'ex: 1.0.0'
                ]
            ])
            ->add('code', null, [
                'attr' => [
                    'placeholder' => '10'
                ]
            ])
            ->add('file', FileType::class)
            ->add('comment', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'materialize-textarea'
                ],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Apk::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'apk';
    }
}
