<?php

namespace App\Api;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Apprenant;
use App\Entity\CommunityManager;
use App\Entity\Competence;
use App\Entity\Formateur;
use App\Entity\Profil;
use App\Entity\ProfilSortie;
use App\Entity\User;

class FilterQueryExtension implements QueryCollectionExtensionInterface
{
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
    {
        if (
            User::class === $resourceClass ||
            Apprenant::class === $resourceClass ||
            Formateur::class === $resourceClass ||
            CommunityManager::class === $resourceClass ||
            Profil::class === $resourceClass ||
            ProfilSortie::class === $resourceClass ||
            Competence::class === $resourceClass
        ) {
            $queryBuilder->andWhere(sprintf("%s.isDeleted = false",
            $queryBuilder->getRootAliases()[0]));
        }
    }
}