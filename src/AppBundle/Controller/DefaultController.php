<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{


    /**
     * @Route("/", name="accueil")
     *
     */
    public function afficherAccueilAction()
    {
        return $this->render('afficher_accueil.html.twig', array());
    }
}
