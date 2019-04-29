<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserFixtures extends Fixture
{
     private $passwordEncoder;
     private $entityManager;


         public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
       {
           $this->passwordEncoder = $passwordEncoder;
           $this->entityManager = $em;
           
 }
    public function load(ObjectManager $manager)
    {

        // $product = new Product();
        // $manager->persist($product);
        $user = new User;

        $user->setEmail('a@a');
        $user->setPassword($this->passwordEncoder->encodePassword(
             $user,      '1234'
      ));
        // $user = $manager->getRepository(User::class)->findOneBy(['email' => 'skeleton@gmail.com']);
        $roles = array( 'ROLE_USER');
        $user->setFirstName('xx');
        $user->setLastName('xx');

        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $manager->flush();
    }
}
