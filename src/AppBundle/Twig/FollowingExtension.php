<?php

namespace AppBundle\Twig;

use Symfony\Bridge\Doctrine\RegistryInterface;

class FollowingExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('following', [
                $this, 'followingFilter'
            ])
        ];
    }

    public function followingFilter($user, $followed)
    {
        $userFollowing = $this->doctrine->getRepository('BackendBundle:Following')->findOneBy([
            'userFollows' => $user,
            'userFollowed' => $followed
        ]);

        if (!empty($userFollowing) && is_object($userFollowing)) {
            $result =  true;
        } else {
            $result = false;
        }

        return $result;
    }

    public function getName()
    {
        return 'following_extension';
    }
}