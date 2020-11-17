<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager) 
    {
        $faker = Faker\Factory::create();
        for($i=0; $i<2; $i++) {
            $profil = $this->getReference(ProfilFixtures::getRefKey(0));
            $user = new User();
            $user
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setEmail('admin'.($i+1).'@test.com')
                ->setProfil($profil)
                ->setPassword($this->encoder->encodePassword($user, "admin"))
            ;

            $manager->persist($user);
        }
        
        for($i=0; $i<2; $i++) {
            $profil = $this->getReference(ProfilFixtures::getRefKey(3));
            $user = new User();
            $user
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setEmail('cm'.($i+1).'@test.com')
                ->setProfil($profil)
                ->setPassword($this->encoder->encodePassword($user, "test"))
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class
        );
    }
}