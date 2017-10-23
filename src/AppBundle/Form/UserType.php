<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('firstname', TextType::class, array(
                'label' => 'First Name:',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-first-name form-control'
                )
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'Last Name:',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-last-name form-control'
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email:',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-email form-control'
                )
            ))
            ->add('nickname', TextType::class, array(
                'label' => 'Nickname:',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-nickname form-control js-nickname-input'
                )
            ))
            ->add('biografy', TextareaType::class, array(
                'label' => 'Biografy:',
                'required' => false,
                'attr' => array(
                    'class' => 'form-pass form-control'
                )
            ))
            ->add('image', FileType::class, array(
                'label' => 'Profile Image:',
                'required' => false,
                'data_class' => null,
                'attr' => array(
                    'class' => 'form-image form-control'
                )
            ))
            ->add('Update Profile', SubmitType::class, array(
                'attr' => array(
                    'class' => 'form-submit btn btn-info'
                )
            ))    ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BackendBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'backendbundle_user';
    }


}
