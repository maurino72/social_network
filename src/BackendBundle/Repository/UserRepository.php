<?php

namespace BackendBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByEmailOrNickname($email, $nickname) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->from('BackendBundle:User', 'u')
            ->where('u.email = :email')
            ->orWhere('u.nickname = :nickname')
            ->setParameter('email', $email)
            ->setParameter('nickname', $nickname);

        return $qb->getQuery()->getResult();
    }

    public function findUserWithSeeker($search)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->from('BackendBundle:User', 'u')
            ->where('u.firstname LIKE :search')
            ->orWhere('u.nickname LIKE :search');
        $qb->setParameter('search', $search);

        return $qb->getQuery()->getResult();
    }
}
