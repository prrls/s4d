<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    /**
    *@Route("/page1", name="page1")
    **/

    public function premierePageAction()
    {
        return $this->render('base.html.twig');
    }


    /**
     *@Route("/page2", name="page2")
     **/
    public function deuxiemePageAction()
    {
        return $this->render('Test/test.html.twig');
    }
}
