<?php

namespace Hcuv\JobeetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('company')
            ->add('logo')
            ->add('url')
            ->add('position')
            ->add('location')
            ->add('description')
            ->add('how_to_apply')
            ->add('token')
            ->add('is_public')
            ->add('is_activated')
            ->add('email')
            ->add('expires_at')
            ->add('created_at')
            ->add('updated_at')
            ->add('category')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Hcuv\JobeetBundle\Entity\Job'
        ));
    }

    public function getName()
    {
        return 'hcuv_jobeetbundle_jobtype';
    }
}
