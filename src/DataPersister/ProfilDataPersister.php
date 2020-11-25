<?php

namespace App\DataPersister;

use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

final class ProfilDataPersister implements ContextAwareDataPersisterInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Profil;
    }

    public function persist($data, array $context = [])
    {
        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data, array $context = [])
    {
        $data->setIsDeleted(true);
        foreach($data->getUsers() as $user) {
            $user->setIsDeleted(true);
        }
        $this->em->flush();
    }
}