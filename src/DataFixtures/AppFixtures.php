<?php

namespace App\DataFixtures;

use App\Entity\Shop;
use App\Entity\User;
use App\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder )
    {
        $this->encoder = $encoder;
    }
    
    private $model = ['iPhone', 'Samsung'];
    private $color = ['black', 'white', 'night green', 'or', 'red', 'blue', 'argent', 'graphite', 'sideral grey'];
    private $go = [ '32', '64', '128', '256'];
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        
        $shop = new Shop();
        $password = $this->encoder->encodePassword($shop,'toto');
        $shop->setName("La boutique a toto")
        ->setEmail("Shop@gmail.com")
        ->setPassword($password)
        ->setAddress($faker->streetAddress())
        ->setCity($faker->city())
        ->setArrivalDate(new \DateTime());
        
        for($i = 1; $i <= 20; $i++) {
            $phone = new Phone();
            $phone->setModel($this->model[rand(0,1)]. ' ' . rand(5, 12));
            $phone->setColor($this->color[rand(0,8)]);
            $phone->setPrice(rand(500, 1000));
            $phone->setDescription('A wonderful phone with ' . $this->go[rand(0,3)] . ' Go');
            
            $manager->persist($phone);
        }
        
        for ( $u = 0; $u < 20; $u++){
            $user = new User();
            $user->setEmail("user$u@gmail.com")
            ->setFirstName($faker->firstNameMale())
            ->setLastName($faker->lastName())
            ->setAddress($faker->streetAddress())
            ->setPostalCode($faker->postcode())
            ->setCity($faker->city())
            ->setCreatedAt(new \DateTime())
            ->setShop($shop);
            
            $manager->persist($user);
        }
        
        $manager->flush();
    }
}
