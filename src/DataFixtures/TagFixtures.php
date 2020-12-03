<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TagFixtures extends Fixture
{
    public static function getRefKey(int $i)
    {
        return sprintf("tag_%s", $i);
    }
    
    public function load(ObjectManager $manager)
    {
        $f = Faker\Factory::create();
        $tags = ["HTML", "CSS", "Angular 10", "Symfony 5.1", "JavaScript", "PHP", "Python"];
        foreach($tags as $k => $libelle){
            $tag = new Tag();
            $tag
                ->setLibelle($libelle)
                ->setDescriptif($f->text());
            $manager->persist($tag);
            $this->addReference(self::getRefKey($k), $tag);
        }
        $manager->flush();
    }
}
