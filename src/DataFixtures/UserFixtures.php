<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
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

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class
        );
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}