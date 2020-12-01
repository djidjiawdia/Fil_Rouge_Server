<?php

namespace App\DataFixtures;

use App\Entity\CommunityManager;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class CommunityManagerFixtures extends Fixture
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
            $profil = $this->getReference(ProfilFixtures::getRefKey(3));
            $user = new CommunityManager();
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