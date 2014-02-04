<?php
namespace NCarolina\FactoryBundle\Controller;

use NCarolina\FactoryBundle\Document\Factory;
use FOS\RestBundle\Controller\FOSRestController;

class FactoryController extends FOSRestController
{
	private function badRequest()
	{
		// Bad request
		$view = $this->view(array(),400);
		$view->setFormat('json');
		return $this->handleView($view);
	}

	private function notFound()
	{
		// Bad request
		$view = $this->view(array(),404);
		$view->setFormat('json');
		return $this->handleView($view);
	}

	private function validatePostFactoryJson($json)
	{
		// Check if json has rootId
		if(isset($json['rootId']) && isset($json['lowerBound']) && isset($json['upperBound']) )
		{
			if(is_int($json['lowerBound']) && is_int($json['upperBound']))
			{
				$uuid_regex = '/^[0-9A-F]{32}$/i';
				$valid_uuid = preg_match_all($uuid_regex, $json['rootId']);
				if($json['lowerBound'] <= $json['upperBound'] && $valid_uuid === 1)
					return true;
			}
		}

		// We don't have valid info
		return false;
	}

	// Create new factory
	public function postFactoryAction()
	{
		$content = $this->get('ncf.json_request')->getJson();

		// Check if we have the right data
		if( !$this->validatePostFactoryJson($content) )
		{
			var_dump($content);
			return $this->badRequest();
		}

		// Find parent document
		$dm = $this->get('doctrine_mongodb')->getManager();
		$root = $this->get('doctrine_mongodb')
				->getRepository('NCarolinaFactoryBundle:Root')
				->findOneById($content['rootId']);

		if(!$root)
			return $this->notFound();

		// Create factory
		$document = new Factory();

		// Create a range
		$document->setPool( range($content['lowerBound'],$content['upperBound']) );
		$document->setText($content['text']);

		// Maintain both sides of relation ship
		$root->addChild($document);
		$document->setParent($root);

		// Persist and save root document
		$dm->persist($document);
		$dm->flush();

		// Set view header 200, send back root id
		$view = $this->view(array('id' => $document->getId()), 200);
		$view->setFormat('json');
		return $this->handleView($view);
	}

	private function validatePostGenerateOutputJson($json)
	{
		// Check if json has rootId
		if(isset($json['factoryId']) && isset($json['number']) )
		{
			if(is_int($json['number']) && $json['number'] < 16)
			{
				$uuid_regex = '/^[0-9A-F]{32}$/i';
				$valid_uuid = preg_match_all($uuid_regex, $json['factoryId']);
				if($valid_uuid === 1)
					return true;
			}
		}

		// We don't have valid info
		return false;
	}

	private function generateOutputs(&$factory, $number)
	{
		// Get output pool
		$pool = $factory->getPool();
		$output = array();

		for($i = 0; $i < $number; $i ++)
		{
			$limit = count($pool);
			$randomIndex = rand(0,$limit - 1);
			$output[] = $pool[$randomIndex];
			unset($pool[$randomIndex]);
			$pool = array_values($pool);
		}

		$factory->setOutput($output);
	}

	public function postGenerateOutputAction()
	{
		$content = $this->get('ncf.json_request')->getJson();

		// Check if we have the right data
		if( !$this->validatePostGenerateOutputJson($content) )
			return $this->badRequest();

		// Find document
		$dm = $this->get('doctrine_mongodb')->getManager();
		$document = $this->get('doctrine_mongodb')
				->getRepository('NCarolinaFactoryBundle:Factory')
				->findOneById($content['factoryId']);

		// Make sure we can find our document
		if(!$document)
			return $this->notFound();

		$pool = $document->getPool();

		// Make sure that we are not making more outputs than
		// pool size.
		if(count($pool) < $content['number'])
			return $this->badRequest();

		$this->generateOutputs($document, $content['number']);

		// Persist and save root document
		$dm->persist($document);
		$dm->flush();

		// Set view header 200, send back root id
		$view = $this->view(array('output' => $document->getOutput()), 200);
		$view->setFormat('json');
		return $this->handleView($view);
	}
}