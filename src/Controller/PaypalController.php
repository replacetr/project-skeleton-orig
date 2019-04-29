<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\CartModel;
use App\Entity\ProductModel;
use App\DomainModels\PayPalDomain;
use App\Entity\OrderModel;
use App\Entity\PaypalModel;
use Symfony\Component\HttpFoundation\Session\Session;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use App\Entity\PaymentModel;
use App\Entity\OrderDetailsModel;


class PaypalController extends AbstractController
{
     /**
     * @Route("/p", name="payls")
     */

    public function index(){
        echo(date("Y-m-d H:i:s"));
        die();
        

    }

     /**
     * @Route("/order/return", name="return_url")
     */
    public function returnUrl(Session $session){

        $paypal = new PayPalDomain;
        $client = $paypal->client();
        $payrequest = new OrdersCaptureRequest($session->get('paypal'));
        $payrequest->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($payrequest);
            
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            // var_dump($response);
        }catch (HttpException $ex) {
            echo $ex->statusCode;
            print_r($ex->getMessage());
        }
        $purchaseUnits = $response->result->purchase_units;
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $total = $this->totalCart();


        $captures = $purchaseUnits[0]->payments->captures;
        $amountPayed = $captures[0]->amount->value;
        $paymentId = $captures[0]->id;
        $createTime = $response->result->create_time;
        $status = $response->result->status;
        $payerEmail = $response->result->payer->email_address;
        //create new data for payment model
        $paymentModel = new PaymentModel;

        $paymentModel->setAmount($amountPayed);
        $paymentModel->setReference($paymentId);
        $paymentModel->setCustomer($user);
        $paymentModel->setPaymentDatetime($createTime);
        $paymentModel->setPaymentStatus($status);
        $paymentModel->setPayerEmail($payerEmail);
        $paymentModel->setPaymentType('PAYPAL');

        $em->persist($paymentModel);
        $em->flush();

        //create new ordermodel
        $order = new OrderModel;
        $order->setOrderTotal($total);
        $order->setCustomer($user);
        $order->setPaymentModel($paymentModel);
        $em->persist($order);
        $em->flush();

        
        $orderId = $response->result->id;
        //set data for paypal
        $paypal = new PaypalModel;
        $paypal->setCustomer($user);
        $paypal->setOrderModel($order);
        $paypal->setPaypalOrderId($orderId);
        $paypal->setDataJson($response);
        $em->persist($paypal);
        $em->flush();
        

        //clear cart after use

        if ($session->get('paypal') == $orderId ){

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
        }  

        


        


        
        return $this->render('paypal\complete.html.twig', [
            'payment'=> $amountPayed, 
            'paymentId' => $paymentId,
            'status' => $status,
            'orderId' => $orderId

            
            ]);

    }
    
    /**
    * @Route("/order/cancel", name="cancel_url")
    */
    public function cancelUrl(){
    
    
    $this->addFlash('warning', 'Paypal Payment has Been Cancel');
    return $this->redirectToRoute('payment');

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
