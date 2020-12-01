<?php

namespace App\Api;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Profil;
use App\Entity\User;

class FilterQueryExtension implements QueryCollectionExtensionInterface
{
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
    {
        if (
            User::class === $resourceClass ||
            Profil::class === $resourceClass
        ) {
            $queryBuilder->andWhere(sprintf("%s.isDeleted = false",
            $queryBuilder->getRootAliases()[0]));
        }
    }
}