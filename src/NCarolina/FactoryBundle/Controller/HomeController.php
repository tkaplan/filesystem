<?php

namespace NCarolina\FactoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('NCarolinaFactoryBundle:home:index.html.twig');
    }
}
