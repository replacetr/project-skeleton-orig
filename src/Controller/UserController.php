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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\ProfileSetupType;
use App\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\CategoryType;
use App\Entity\CategoryModel;
use App\Entity\OrderModel;




class UserController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {

        return $this->redirectToRoute('home');
        // return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/home",name="home")
     * Method({"GET", "POST"})
     */
    public function homeWhere(Request $request, PaginatorInterface $paginator) {

        $repository = $this->getDoctrine()->getRepository(ProductModel::class)->findAll();
    

        $pagination = $paginator->paginate(
            $repository, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        

        // $package = new Package(new StaticVersionStrategy('v1'));
        // $imagePath = $package->getUrl('/images/logo.png');
       return $this->render('user/home.html.twig', ['pagination' => $pagination]);



    }

 
    /**
     * @Route("/view/{id}", name="app_view")
     * Method({"POST","GET"})
     */
    public function view($id){
        $repository = $this->getDoctrine()->getRepository(ProductModel::class)->find($id);

        return $this->render('user/view.html.twig', ['product' => $repository]);
    }
    /**
     * @Route("/cart/{product}", name="add_cart")
     * Method({"POST","GET"})
     */
    public function addCart($product,EntityManagerInterface $em,ManagerRegistry $manager){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser()->getid();
        // $ti = new ProductDomain($);
        $cart = new CartDomain($em,$manager);
        $cart->addCart($user,$product);
         return $this->redirectToRoute('view_cart');


    }
 

    /**
     * @Route("/pchange", name="user_change_password")
     * Method({"POST","GET"})
     */
    public function changePassword(Request $request,UserPasswordEncoderInterface $passwordEncoder){


        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(ProfileSetupType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();
            

            $user = $this->getUser();

            if($passwordEncoder->isPasswordValid($user,$data['oldPassword']))
            {
                if ($data['oldPassword'] == $data['password']){
                    $this->addflash('warning', 'Password not changed.'); 
                }
                else{
                    
                    
                    
                    $em = $this->getDoctrine()->getManager();
                    $newUser = $em->getRepository(User::class)->find($user->getId());
                    $newUser->setPassword($passwordEncoder->encodePassword($newUser, $data['password']));
                    $em->flush();
                    
                    // todo next version of project email to user new password change
                    $this->addflash('success', 'Your password has been changed. Please use your New Password when login again.');

                    
                }
            }
            
            
            else{

                $this->addflash('warning', 'Wrong password.'); 
            }


        }

        return $this->render('user/changePassword.html.twig', ['form' => $form->createView()]);


  

    }

    
    public function searchBarAction(){
        $form = $this->createFormBuilder()
        ->add('search', TextType::class)
        ->getForm();

        return $this->render('searchbar.html.twig' , ['form' => $form->createView() ]);
    }

      /**
     * @Route("/searching", name="search_bar")
     */

    public function handleSearch (request $request, PaginatorInterface $paginator){

        $search = $request->request->get('form');
        // var_dump($search['search']);


        $product = $this->getDoctrine()->getRepository(ProductModel::class)->search( $search['search']);

        $pagination = $paginator->paginate(
            $product, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        return $this->render('user/home.html.twig',  ['pagination' => $pagination ]);
        
    }

    /**
    * @Route("/user/order", name="user_order")
    */

    public function userOrder()
    {
        $user = $this->getUser();
        $order = $this->getDoctrine()->getRepository(OrderModel::class)->findBy(['customer' => $user]);
//         dump($order);
// die();
        return$this->render('user/order.html.twig', ['orders' => $order]);

    }


    





}
