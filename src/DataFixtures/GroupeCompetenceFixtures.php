<?php

namespace App\DataFixtures;

use App\Entity\GroupeCompetence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class GroupeCompetenceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $f = Faker\Factory::create();
        for($i=1; $i<=5; $i++){
            $grc = new GroupeCompetence();

            $grc
                ->setLibelle($f->text(50))
                ->setDescriptif($f->paragraph(2));
            for($j=0; $j<2; $j++){
                $competence = $this->getReference(CompetenceFixtures::getRefKey($f->randomDigitNotNull)); 
                $grc->addCompetence($competence);  
            }

            $manager->persist($grc);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CompetenceFixtures::class
        );
    }
}
