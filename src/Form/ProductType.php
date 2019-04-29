<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\ProductModel;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('product_name', TextType::class, array('label' => 'Name','attr' => array('class' => 'form-control')))
        ->add('product_price', MoneyType::class,[ 'label' => 'Price :', 'currency' => 'MYR', 'attr' => ['class' => 'form-control'],])
        ->add('product_stock', IntegerType::class,array('label' => 'Stock','attr' => array('class' => 'form-control')))
        ->add('product_image', FileType::class,array('label' => 'Image Files (JPEG,PNG)'))
        ->add('save', SubmitType::class,array(
            'label' => 'Create',
            'attr' => array('class' => 'btn btn-primary mt-3')))
        ;



    }
    public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults([
        'data_class' => ProductModel::class,
    ]);
}
}