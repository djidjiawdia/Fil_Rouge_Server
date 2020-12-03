<?php

namespace App\DataFixtures;

use App\Entity\GroupeTag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class GroupeTagFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $f = Faker\Factory::create();
        for($i=0; $i<5; $i++){
            $grptag = new GroupeTag();
            $grptag->setLibelle($f->sentence(2));
            for($j=0; $j<3; $j++){
                $tag = $this->getReference(TagFixtures::getRefKey($f->numberBetween(0,6)));
                $grptag->addTag($tag);
            }
            $manager->persist($grptag);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TagFixtures::class
        ];
    }
}
