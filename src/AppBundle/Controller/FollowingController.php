<?php
/**
 * Created by PhpStorm.
 * User: bmaurino
 * Date: 10/24/17
 * Time: 10:31
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Entity\Following;
use Symfony\Component\HttpFoundation\Session\Session;
use BackendBundle\Entity\User;

class FollowingController extends Controller
{
    protected $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function followAction(Request $request)
    {
        echo 'Follow Action';
        die;
    }

}