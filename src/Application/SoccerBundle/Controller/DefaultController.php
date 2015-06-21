<?php

namespace Application\SoccerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('ApplicationSoccerBundle:Default:index.html.twig', array());
    }

}
