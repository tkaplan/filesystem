<?php

namespace NCarolina\FactoryBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use NCarolina\FactoryBundle\Document\Root;

class RootController extends FOSRestController
{
	// Create a root document
	public function postRootAction()
	{
		$dm = $this->get('doctrine_mongodb')->getManager();

		$args = array();
		$content = $this->get('ncf.json_request')->getJson();

		// Check if we have the right data
		if( NULL == $content || !isset($content['text']) )
		{
			// Bad request
			$view = $this->view(array(),400);
			$view->setFormat('json');
			return $this->handleView($view);
		}

		$text = $content['text'];
		// Create root document
		$document = new Root();

		// Set root document text
		$document->setText($text);
		$document->setType('ROOT');

		// Persist and save root document
		$dm->persist($document);
		$dm->flush();

		// Set view header 200, send back root id
		$view = $this->view(array('id' => $document->getId()), 200);
		$view->setFormat('json');
		return $this->handleView($view);
	}
};