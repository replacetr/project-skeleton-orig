<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\ProductModel;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProductType;
use App\Form\UserRegisterType;
use App\DomainModels\RegisterDomain;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\ProductModelType;
use App\Entity\CategoryModel;
use App\Form\CategoryType;




class AdminController extends AbstractController



{
    
     /**
     * @Route("/admin", name="admin_index")
     * Method({"POST","GET"})
     */
    public function index(){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/adminBase.html.twig');


    }

     /**
     * @Route("/adduser", name="user_add")
     * Method({"POST","GET"})
     */
    public function adduser(Request $request,UrlGeneratorInterface $urlGeneratorInterface,SessionInterface $ses,EntityManagerInterface $em,UserPasswordEncoderInterface $passEn){
        $form = $this->createForm(UserRegisterType::class);



        if ($request->isMethod('POST'))
            {
                $register = new RegisterDomain($urlGeneratorInterface,$ses,$passEn);
                return $register->submited($request,$form,$em);
            }
        return $this->render('admin/user.html.twig',[ 'form' => $form->createView()]);
        
        
        
    }



    /**
    * @Route("/newproduct", name="new_product")
    * Method({"GET", "POST"})
    */
    public function newproduct(Request $request) {

        $product = new ProductModel;
        $form = $this->createForm(ProductModelType::class);

        $form->handleRequest($request);

           if ($form->isSubmitted() && $form->isValid()){
    
    
            
            $file = $form['product_image']->getData();
            if($file->guessExtension() != 'JPEG' || 'JPG' || PNG )

            
            $filename = md5(uniqid()) .'.'.$file->guessExtension();
         
            try {
                $file->move(
                $this->getParameter('image_directory'),
                $filename
                );
            } catch (FileException $e) {
            
                $filename = 'NoImage.jpeg';
            }
            $entityManager = $this->getDoctrine()->getManager();
            $product->setProductImage($filename);
            $data = $form->getData();
            if(empty($entityManager->getRepository(CategoryModel::class)->findAll()))
            {
                
                
                $category = new CategoryModel;            
                $category->setCategoryName($form['category']->getData());
                $entityManager->persist($category);
                $entityManager->flush();
                $product->setCategory($category);
                
            }
            else
                {
                    $product->setCategory($data['category']);
                }
            $product->setDescription($data['description']);
            $product->setProductName($data['product_name']);
            $product->setProductPrice($data['product_price']);
            $product->setProductStock($data['product_stock']);
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('home');
            }
            return $this->render('admin/product.html.twig',[
                'form' => $form->createView(),
            ]);
    }

    /**
     * @route("/addcategory/{id}", name="add_category")
     * Method({"POST","GET"})
     */
    public function addCategory($id = null,Request $request){

        $newCategory = new CategoryModel();
        $form = $this->createForm(CategoryType::class,$newCategory);

        $category = $this->getDoctrine()->getRepository(CategoryModel::class)->findAll();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $em->persist($newCategory);
            $em->flush();

            return $this->redirectToRoute('add_category'); 

        }
        if($id){
            $product = $this->getDoctrine()->getRepository(ProductModel::class)->findBy( ['category' => $id ]);
            if (!empty($product))
            {
                foreach($product as $products){
                $em = $this->getDoctrine()->getManager();
                $em->remove($products);
                $em->flush();
                }
            }
            
            $em = $this->getDoctrine()->getManager();
            $del = $em->getRepository(CategoryModel::class)->find($id);
            $em->remove($del);
            $em->flush();

            
            return $this->redirectToRoute('add_category'); 
            }
        return $this->render('admin/category.html.twig', ['form' => $form->createView() , 'categories' =>  $category] );
    }


  

 
    
}