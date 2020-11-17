<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Apprenant;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements DependentFixtureInterface
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
            $profil = $this->getReference(ProfilFixtures::getRefKey(2));
            $user = new Apprenant();
            $user
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setEmail('apprenant'.($i+1).'@test.com')
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
