<?php


namespace App\Controller;


use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @method User getUser()
 */
abstract class BaseController extends AbstractController
{
//    protected function getUser(): User
//    {
//        return parent::getUser();
//    }
}