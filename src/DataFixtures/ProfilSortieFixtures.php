<?php

namespace App\DataFixtures;

use App\Entity\ProfilSortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilSortieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $ps = ["Développeur Front", "Back", "Fullstack", "CMS", "Intégrateur", "Designer"];

        foreach($ps as $libelle) {
            $profilSortie = new ProfilSortie();
            $profilSortie->setLibelle($libelle);
            $manager->persist($profilSortie);
        }

        $manager->flush();
    }
}
