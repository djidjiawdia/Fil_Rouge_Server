<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Formateur;
use App\DataFixtures\ProfilFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormateurFixtures extends Fixture implements DependentFixtureInterface
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
            $profil = $this->getReference(ProfilFixtures::getRefKey(1));
            $user = new Formateur();
            $user
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setEmail('formateur'.($i+1).'@test.com')
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
