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
use Symfony\Bridge\Doctrine\RegistryInterface;

class LikeExtension extends \Twig_Extension
{
    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('liked', [
                $this, 'likedFilter'
            ])
        ];
    }

    public function likedFilter(User $user, Publication $publication)
    {
        $publicationLike = $this->doctrine->getRepository('BackendBundle:Like')->findOneBy([
            'user' => $user,
            'publication' => $publication
        ]);

        if (!empty($publicationLike) && is_object($publicationLike)) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    public function getName()
    {
        return 'liked_extension';
    }
}