<?php
/**
 * Created by PhpStorm.
 * User: bmaurino
 * Date: 11/1/17
 * Time: 08:36
 */

namespace AppBundle\Twig;


use BackendBundle\Entity\Publication;
use BackendBundle\Entity\User;
use BackendBundle\Entity\Like;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserStatsExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('user_stats', [
                $this, 'userStatsFilter'
            ])
        ];
    }

    public function userStatsFilter(User $user)
    {
        $likes = $this->doctrine->getRepository('BackendBundle:Like')->findBy([
            'user' => $user,
        ]);

        $following = $this->doctrine->getRepository('BackendBundle:Following')->findBy([
            'userFollows' => $user
        ]);

        $followers = $this->doctrine->getRepository('BackendBundle:Following')->findBy([
            'userFollowed' => $user
        ]);

        $publications = $this->doctrine->getRepository('BackendBundle:Publication')->findBy([
            'user' => $user
        ]);

        $result =  [
            'following' => count($following),
            'followers' => count($followers),
            'publications' => count($publications),
            'likes' => count($likes)
        ];

        return $result;

    }

    public function getName()
    {
        return 'user_stats_extension';
    }
}