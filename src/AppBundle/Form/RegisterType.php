<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterType extends AbstractType
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
            ->add('nickname', TextType::class, array(
                'label' => 'Nickname:',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-nickname form-control js-nickname-input'
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email:',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-email form-control'
                )
            ))
            ->add('password', PasswordType::class, array(
                'label' => 'Password:',
                'required' => 'required',
                'attr' => array(
                    'class' => 'form-pass form-control'
                )
            ))
            ->add('SignUp', SubmitType::class, array(
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
