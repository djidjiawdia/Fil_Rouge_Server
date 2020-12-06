<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Referentiel;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\GroupeCompetenceFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReferentielFixtures extends Fixture implements DependentFixtureInterface
{
    
    public function load(ObjectManager $manager)
    {
        $f = Faker\Factory::create();
        
        for ($i=1; $i<=5; $i++) {
            $ref = new Referentiel();

            $ref
                ->setLibelle($f->word)
                ->setPresentation($f->sentence)
                ->setCritereEvaluation($f->paragraph(2))
                ->setCritereAdmission($f->paragraph(2));

            for ($j=0; $j<3; $j++) {
                $grpc = $this->getReference(GroupeCompetenceFixtures::getRefKey($f->numberBetween(1,5)));
                $ref->addGroupeCompetence($grpc);
            }

            $manager->persist($ref);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            GroupeCompetenceFixtures::class
        ];
    }
}
