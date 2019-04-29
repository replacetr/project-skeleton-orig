<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

// ...

class UserRegisterType extends AbstractType
{   
    // Private $request;

    // public function __construct(Request $request)
    // {
    //     $this->request = $request;
    // }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
        ->add('email', EmailType::class)
        ->add('password', RepeatedType::class, [
            'invalid_message' => 'The password fields must match',
            'type' => PasswordType::class,
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => ['label' => 'Password'],
            'second_options' => ['label' => 'Repeat Password'],
        ])
        ->add('firstName')
        ->add('lastName')
        ->add('roles', ChoiceType::class, [
            'choices' => [
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN'
                ]
        ])
        ->add('includeAddress', CheckboxType::class,['required' => false ])
        ->add('address')
        ->add('postcode', NumberType::class)
        ->add('city')
        ->add('state')
        ->add('country')
        ->add('contactNo')

        ->add('submit', SubmitType::class, [
            'attr' => ['class' => 'save','onload' => 'start()']
        ])


        
        ;

    

  
    }



}