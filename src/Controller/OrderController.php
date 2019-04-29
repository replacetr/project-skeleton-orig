<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\CartModel;
use App\Entity\ProductModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\DomainModels\PayPalDomain;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\OrderModel;
use App\Entity\PaymentModel;
use App\Entity\OrderDetailsModel;


class OrderController extends AbstractController
{
    Private $client;
    /**
     * @Route("/order", name="order")
     */
    public function index()
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }
    /**
     * @Route("/order/payment", name="payment")
     */
    public function payment(Request $request){
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser()->getId();
        $carts = $this->getDoctrine()->getRepository(CartModel ::class)->findBy(['user_id' => $user]);

        if (empty($carts)){
            $this->addFlash('warning', 'You dont have item in your shopping cart');
            return $this->redirectToRoute('home');
        }
        $i = 0;
        $total = 0;
        foreach($carts as $cart){
            $product = $this->getDoctrine()->getRepository(ProductModel::class)->find($cart->getProductId());   
            $total = $total + ($product->getProductPrice() * $cart->getQty());

            $stock[$i] = $product;
            $i++;
        }
        $form = $this->createFormBuilder()
        ->add('paypal', SubmitType::class, ['label' => 'Paypal'])
        ->add('demo', SubmitType::class, ['label' => 'Demo Payment'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
        
            $redirect = $form->get('paypal')->isClicked()
                ? 'pay_pal'
                : 'pay_demo';
        
            return $this->redirectToRoute($redirect);
        }
        return $this->render('order/payment.html.twig', ['form' => $form->createView(), 'products' => $carts , 'stock' => $stock , 'total' => $total]);
        }

    /**
     * @Route("/order/paypal", name="pay_pal")
     */
    public function paypalPayment(UrlGeneratorInterface $url, RequestStack $requestStack, Session $session){
        // For demo purpose only using my paypal account..for further details please contact shaifulazhartalib@gmail 
        $this->denyAccessUnlessGranted('ROLE_USER');
        $total = $this->totalCart();
        if(!$total){
            $this->addFlash('warning', 'You dont have item in your shopping cart');
            return $this->redirectToRoute('home');
        }
        $paypalTotal = number_format((float)$total, 2, '.', '');
        $paypal = new PayPalDomain;
        $client = $paypal->client();


        $baseUrl = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $referenceId =  md5(uniqid()) . $this->getUser()->getId();
        $session->set('reference_id', $referenceId);

        
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');

        // $request->body = $this->buildRequestBody();
        $request->body = [
                            "reference_id" => $referenceId,
                            "description" => "SKELETONWEB",
                             "intent" => "CAPTURE",
                             "purchase_units" => [[
                                     "amount" => [
                                     "currency_code" => "MYR",
                                     "value" => $paypalTotal,
                                     ]
                             ]],
                             "application_context" => [
                                  "cancel_url" => $baseUrl . $url->generate('cancel_url'),
                                  "return_url" => $baseUrl . $url->generate('return_url'),
                                  "brand_name" => "PROJECT SKELETON",
                                  "user_action" => "PAY_NOW",
                             ] 
                         ];
        
        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($request);
            
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            // print_r($response);
        }catch (HttpException $ex) {
             $this->addFlash('danger' , $ex->statusCode . $ex->getMessage());
            return $this->redirectToRoute('home');
        }

     
        $id = $response->result->id;
        $session->set('paypal', $id);

        // $links = $response->result->links;

        foreach ($response->result->links as $key => $value) {
            if ($value->rel == 'approve'){
              $redirect = $value->href;
            }
          }

         return $this->redirect($redirect);


    }

     /**
     * @Route("/order/demo", name="pay_demo")
     */
    public function demoPayment(Session $session){

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $total = $this->totalCart();

        //set payment attributes

        $paymentId = uniqid();
        $paymentModel = new PaymentModel;
        
        $paymentModel->setAmount($total);
        $paymentModel->setReference($paymentId);
        $paymentModel->setCustomer($user);
        $paymentModel->setPaymentDatetime(date("Y-m-d H:i:s")); //timestamp now
        $paymentModel->setPaymentStatus('COMPLETED');
        $paymentModel->setPayerEmail($user->getEmail());
        $paymentModel->setPaymentType('DEMO');

        $em->persist($paymentModel);
        $em->flush();

        //create new ordermodel
        $order = new OrderModel;
        $order->setOrderTotal($total);
        $order->setCustomer($user);
        $order->setPaymentModel($paymentModel);
        $em->persist($order);
        $em->flush();


            $carts = $em->getRepository(CartModel::class)->findBy(['user_id' => $user]);
            foreach($carts as $cart){
                $product = $em->getRepository(ProductModel::class)->find($cart->getProductId());
                $stockleft = $product->getProductStock() - $cart->getQty();
                $product->setProductStock($stockleft);
                $em->persist($product);
                $em->flush();

                $orderDetails = new OrderDetailsModel;
                $orderDetails->setOrderModel($order);
                $orderDetails->setProductModel($product);
                $orderDetails->setProductPrice($product->getProductPrice());
                $orderDetails->setQty($cart->getQty());
                $em->persist($orderDetails);
                $em->flush();

                $em->remove($cart);
                $em->flush();
            }


            return $this->render('paypal\complete.html.twig', [
                'payment'=> $total, 
                'paymentId' => $paymentId,
                'status' => 'COMPLETED',
                'orderId' => $order->getId()
    
                
                ]);
          



    }



    public function totalCart(){
        $user = $this->getUser()->getId();
        $carts = $this->getDoctrine()->getRepository(CartModel ::class)->findBy(['user_id' => $user]);

        if (empty($carts)){
            return null;
        }
        $total = 0;
        foreach($carts as $cart){
            $product = $this->getDoctrine()->getRepository(ProductModel::class)->find($cart->getProductId());   
            $total = $total + ($product->getProductPrice() * $cart->getQty());
        }

        return $total;
    }


 
    

   

    

}
