<?php

namespace BackendBundle\Repository;

/**
 * PublicationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PublicationRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllPublicationsFromUsersFollowed($user_id, $following)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from('BackendBundle:Publication', 'p')
            ->where('p.user = :user_id')
            ->orWhere('p.user IN (:following)')
            ->orderBy('p.id', 'DESC');

        $qb->setParameter('user_id', $user_id);
        $qb->setParameter('following', $following);

        return $qb->getQuery()->getResult();
    }

    public function findUserPublications($userId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p')
            ->from('BackendBundle:Publication', 'p')
            ->where('p.user = :user_id')
            ->setParameter('user_id', $userId)
            ->orderBy('p.id', 'DESC');
        return $qb->getQuery()->getResult();
    }
}
