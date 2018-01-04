<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;

/**
 * ProjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends BaseRepository
{

    /**
     * @param User|null $excludeUser
     * @param array     $orderBy
     *
     * @return array
     */
    public function findPublicExcludeUser(User $excludeUser = null, $orderBy = [])
    {
        return $this->findProjectByContextAndExcludeUserQuery(Project::CONTEXT_PUBLIC, $excludeUser, $orderBy)->getQuery()->getResult();
    }

    /**
     * @param User|null $excludeUser
     * @param array     $orderBy
     *
     * @return array
     */
    public function findPrivateExcludeUser(User $excludeUser = null, $orderBy = [])
    {
        return $this->findProjectByContextAndExcludeUserQuery(Project::CONTEXT_PRIVATE, $excludeUser, $orderBy)->getQuery()->getResult();
    }

    /**
     * @param      $context
     * @param User $excludeUser
     * @param      $orderBy
     * @param      $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function findProjectByContextAndExcludeUserQuery($context, User $excludeUser, $orderBy, $alias = 'p')
    {
        $qb = $this->createQueryBuilder($alias);
        $qb
            ->where(
                $qb->expr()->eq($alias . '.context', ':context')
            )
            ->setParameter('context', $context)
        ;
        if ($excludeUser) {
            $qb
                ->andWhere(
                    $qb->expr()->neq($alias . '.creator', ':user')
                )
                ->setParameter('user', $excludeUser->getId())
            ;
        }

        foreach ($orderBy as $sort => $order) {
            $qb->addOrderBy($alias . '.' . $sort, $order);
        }
        return $qb;
    }
}
