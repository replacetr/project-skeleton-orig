<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\CategoryModel;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityManagerInterface;


class ProductModelType extends AbstractType
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('product_name', TextType::class, array('label' => 'Name','attr' => array('class' => 'form-control', 'style' => 'width:20em')))
        ->add('product_price', MoneyType::class,[ 'label' => 'Price :', 'currency' => 'MYR', 'attr' => ['class' => 'form-control', 'style' => 'width:20em'],])
        ->add('product_stock', IntegerType::class,array('label' => 'Stock','attr' => array('class' => 'form-control', 'style' => 'width:5em')))
        ->add('product_image', FileType::class,array('label' => 'Image Files (JPEG,PNG) with size of 700x400 pixel', 'attr' => ['class' => 'form-control-file']))
        ->add('description', TextareaType::class, ['label' => 'Description', 'attr' => ['class' => 'form-control', 'style' => 'width:20em']])
        ->add('submit' , SubmitType::class, ['attr' => ['class'=>'btn btn-primary']])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $category = $this->em->getRepository(CategoryModel::class)->findAll();
                if (!empty($category)){
                    $form->add('category', EntityType::class, [
                        'class' => CategoryModel::class,
                        'choice_label' => 'category_name',
                    ]);


                }
                else{
                    $form->add('category');
                }

                

                
            }
        );




    }
 

}
