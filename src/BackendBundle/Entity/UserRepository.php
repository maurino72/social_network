<?php
/**
 * Created by PhpStorm.
 * User: bmaurino
 * Date: 10/5/17
 * Time: 12:15
 */

namespace BackendBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByEmailOrNickname($email, $nickname) {
        $qb = $this->createQueryBuilder();
        $qb->select('u')
            ->from('BackendBundle:User')
            ->where('u.email = :email')
            ->orWhere('u.nickname = :nickname')
            ->setParameter('email', $email)
            ->setParameter('nickname', $nickname);

        return $qb->getQuery()->getResult();
    }
}