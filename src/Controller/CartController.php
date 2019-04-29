<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\ProductModel;
use App\Entity\CartModel;
use Doctrine\ORM\EntityManagerInterface;
use App\DomainModels\CartDomain;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class CartController extends AbstractController
{
    
    /**
     * @Route("/remove/{product}", name="remove_cart")
     * Method({"POST","GET"})
     */
    public function removeCart($product,EntityManagerInterface $em,ManagerRegistry $manager){
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser()->getid();
        $cart = new CartDomain($em,$manager);
        $cart->removeCart($user,$product);
        return $this->redirectToRoute('view_cart');
        
    }
    /**
     * @Route("/viewcart", name="view_cart")
     * Method({"POST","GET"})
     */
    public function viewCart(Request $request){
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser()->getid();
        $carts = $this->getDoctrine()->getRepository(CartModel::class)->findByUserId($user);
        $total = $this->getDoctrine()->getRepository(CartModel::class)->cartTotal($user);
        // $stock = todo stock 

        if (empty($carts)){
            $form = null;
            $stock = null;
        }
        $i = 0;
        foreach ($carts as $cart ){
            $stock[$i] = $this->getDoctrine()->getRepository(ProductModel::class)->find($cart->getProductId());
            $form[$i] = $this->createFormBuilder()
            ->add('qty', IntegerType::class,array('label' => 'Stock','attr' => array('style' => 'width: 4em','min' => '1','max' => '100',)))
            ->add('product_id', HiddenType::class,[ 'data' => $cart->getProductId()])
            ->add('user_id', HiddenType::class,[ 'data' => $user])
            ->add('save', SubmitType::class,array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm()->createView();
            
            $i++;
        }
        
        $checkout = $this->createFormBuilder()
        ->add('checkout', HiddenType::class,[ 'data' => 'betul'])
        ->add('save', SubmitType::class,array(
            'label' => 'Check Out',
            'attr' => array('class' => 'btn btn-primary mt-3')))
        ->getForm()->createView();
        
        if ($request->isMethod('Post')) {
            
            //check if stock is available
            $stockErr = 0;
            foreach ($carts as $cart){
                if ($this->getDoctrine()->getRepository(ProductModel::class)->find($cart->getProductId())->getProductStock() - $cart->getQty() < 0 )
                {
                    $stockErr++;
                }
            }
            //stock is good means $stockErr is 0
            if ($stockErr == 0 ){
                
                return $this->redirectToRoute('payment');

            }
            else{
                $this->addFlash('warning','Not Enough Stock');

            }
        }
    
        return $this->render('cart/view.html.twig', ['products' => $carts, 'total' => $total , 'form' => $form, 'stock' => $stock, 'checkout' => $checkout]);

    }


    /**
     * @Route("/updatecart", name="update_cart")
     * Method({"POST","GET"})
     */
    public function updateCart(EntityManagerInterface $em,ManagerRegistry $manager,Request $request){

    
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser()->getid();
        $get = $request->query->get('form');
        $cart = new CartDomain($em,$manager);
        $cart->updateCart($get['qty'],$user,$get['product_id']);
 
      
        return $this->redirectToRoute('view_cart');
        

    }

}
