<?php

namespace App\DomainModels;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\UserDetails;
use Symfony\Component\HttpFoundation\Response;



Class RegisterDomain{

    Private $urlGeneratorInterface;
    private $sess;
    private $passEn;

    public function __construct(UrlGeneratorInterface $urlGeneratorInterface,SessionInterface $sess,UserPasswordEncoderInterface $passEn)
    {
        $this->urlGeneratorInterface = $urlGeneratorInterface;
        $this->sess = $sess;
        $this->passEn = $passEn;
    }

    public function submited(Request $request,$form,$em)
    {

        $form->submit($request->request->get($form->getName()));


        if ($form->isSubmitted() && $form->isValid()) {

            $user = new User;
            $data = $form->getData();

            //check email if already exist
            

            if($em->getRepository(User::class)->findOneBy(['email' => $data['email']]))
            {
               
               
                $this->sess->getFlashBag()->add('warning', 'EMAIL IS ALREADY EXIST');
                return new RedirectResponse($this->urlGeneratorInterface->generate('user_add'));
                
            }
            else{


                $user->setEmail($data['email']);
                $user->setFirstName($data['firstName']);
                $user->setLastName($data['lastName']);
                $user->setPassword($this->passEn->encodePassword($user , $data['password']));
                $array[] = $data['roles'];
                $user->setRoles($array);
                
                if(!$data['includeAddress'] )
                {
                    $userDetails = new UserDetails;
                    $userDetails->setAddress($data['address']);
                    $userDetails->setCity($data['city']);
                    $userDetails->setContactNo($data['contactNo']);
                    $userDetails->setCountry($data['country']);
                    $userDetails->setPostcode($data['postcode']);
                    $userDetails->setState($data['state']);
                    $userDetails->setUser($user);
                    
                    $em->persist($user);
                    $em->persist($userDetails);
                    $em->flush();

                    $this->sess->getFlashBag()->add('success', 'Hello' . $user->getFirstName()  . 'You Are Registered. Please Contine Login');   
                
                    return new RedirectResponse($this->urlGeneratorInterface->generate('app_login'));

                
                }

                $em->persist($user);
                $em->flush();


                $this->sess->getFlashBag()->add('success', 'Hello' . $user->getFirstName() .'You Are Registered. Please Contine Login using your email');   
                
                return new RedirectResponse($this->urlGeneratorInterface->generate('app_login'));
            }
          
        }
        $this->sess->getFlashBag()->add('warning', 'FORM IS NOT VALID'); 
        return new RedirectResponse($this->urlGeneratorInterface->generate('user_add'));

    }

   
}