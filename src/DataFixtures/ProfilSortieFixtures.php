<?php

namespace App\DataFixtures;

use App\Entity\ProfilSortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilSortieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $ps = [
            "Développeur Front",
            "Développeur Back",
            "Développeur Fullstack",
            "Référent Digital",
            "Intégrateur web",
            "Designer web",
            "Data scientist",
            "IOT",
            "Community Manager"
        ];

        foreach($ps as $libelle) {
            $profilSortie = new ProfilSortie();
            $profilSortie->setLibelle($libelle);
            $manager->persist($profilSortie);
        }

        $manager->flush();
    }
}
