<?php

namespace BackendBundle\Repository;

/**
 * LikeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LikeRepository extends \Doctrine\ORM\EntityRepository
{
    public function findPublicationLikesByUser($userId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('l')
            ->from('BackendBundle:Like', 'l')
            ->join('BackendBundle:Publication', 'p')
            ->where('l.user = :user_id')
            ->setParameter('user_id', $userId)
            ->orderBy('p.id', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
