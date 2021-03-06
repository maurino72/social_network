<?php

namespace BackendBundle\Entity;

/**
 * Following
 */
class Following
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $userFollows;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $userFollowed;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userFollows
     *
     * @param \BackendBundle\Entity\User $userFollows
     *
     * @return Following
     */
    public function setUserFollows(\BackendBundle\Entity\User $userFollows = null)
    {
        $this->userFollows = $userFollows;

        return $this;
    }

    /**
     * Get userFollows
     *
     * @return \BackendBundle\Entity\User
     */
    public function getUserFollows()
    {
        return $this->userFollows;
    }

    /**
     * Set userFollowed
     *
     * @param \BackendBundle\Entity\User $userFollowed
     *
     * @return Following
     */
    public function setUserFollowed(\BackendBundle\Entity\User $userFollowed = null)
    {
        $this->userFollowed = $userFollowed;

        return $this;
    }

    /**
     * Get userFollowed
     *
     * @return \BackendBundle\Entity\User
     */
    public function getUserFollowed()
    {
        return $this->userFollowed;
    }
}
