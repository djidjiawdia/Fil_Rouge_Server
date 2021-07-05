<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class ProfilFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getRefKey(int $i)
    {
        return sprintf("profil_%s", $i);
    }
    
    public function load(ObjectManager $manager){

        $profils = ["admin", "formateur", "apprenant", "cm"];
        
        foreach($profils as $k => $libelle) {
            $profil = new Profil();
            $profil->setLibelle($libelle);
            $this->addReference(self::getRefKey($k), $profil);
            $manager->persist($profil);
        }
        
        $manager->flush();
    }


    public static function getGroups(): array
    {
        return ['group1'];
    }

}