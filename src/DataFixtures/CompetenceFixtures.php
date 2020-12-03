<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use App\Entity\Niveau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CompetenceFixtures extends Fixture
{
    public static function getRefKey(int $i)
    {
        return sprintf("comp_%s", $i);
    }

    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();

        for($i=1; $i<=10; $i++) {
            $competence = new Competence();

            $competence->setLibelle($faker->sentence(4));
            for($j=1; $j<=3; $j++){
                $niveau = new Niveau();
                $niveau
                    ->setLibelle('Niveau '.$j)
                    ->setCritereEvaluation($faker->text())
                    ->setGroupeAction($faker->text());
                $competence->addNiveau($niveau);
            }
            $manager->persist($competence);
            $this->addReference(self::getRefKey($i), $competence);
        }

        $manager->flush();
    }
}
