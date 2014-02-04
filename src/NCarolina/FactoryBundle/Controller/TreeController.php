<?php

namespace NCarolina\FactoryBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use NCarolina\Document\Root;

class TreeController extends FOSRestController
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

	public function getTreeAction()
	{
		// Get repository manager
		$roots = $this->get('doctrine_mongodb')
				->getRepository('NCarolinaFactoryBundle:Root')
				->findAll();
		
		$tree = array();

		// Find all roots
		foreach($roots as $root)
		{
			$rootData = array();

			// Pack root info
			$rootData['id'] = $root->getId();
			$rootData['type'] = 'ROOT';
			$rootData['text'] = $root->getText();
			$rootData['children'] = array();
			
			$factories = $root->getChildren();

			if( null == $factories )
			{
				$tree[] = $rootData;
				continue;
			}
			
			// Pack factories
			foreach($factories as $factory)
			{
				$factoryData = array();

				// Pack factory data
				try
				{
					$factoryData['id'] = $factory->getId();
					$factoryData['type'] = 'FACTORY';
					$factoryPool = $factory->getPool();
					$factoryData['lowerBound'] = $factoryPool[0];
					$factoryData['upperBound'] = $factoryPool[count($factoryPool) - 1];
					$factoryData['text'] = $factory->getText() . ": " . $factoryData['lowerBound'] . " - " . $factoryData['upperBound'];
					$factoryData['children'] = array();
				}
				catch(\Exception $e)
				{
					// Don't bother with the factory
					continue;
				}

				if( null == ($outputs = $factory->getOutput()) )
				{
					$rootData['children'][] = $factoryData;
					continue;
				}

				// Pack outputs
				foreach($outputs as $key => $output)
				{

					$outputData['id'] = rand(0,10000000);
					$outputData['type'] = 'OUTPUT';
					$outputData['text'] = $output;

					// Push data onto children
					$factoryData['children'][] = $outputData;
				}

				// Push data onto children
				$rootData['children'][] = $factoryData;
			}

			$tree[] = $rootData;
		}

		// Send out data now
		$view = $this->view($tree, 200)
					->setFormat('json');
		return $this->handleView($view);
	}

	private function validateUUID($uuid)
	{
		$uuid_regex = '/^[0-9A-F]{32}$/i';
		$valid_uuid = preg_match_all($uuid_regex, $uuid);

		if($valid_uuid === 1)
			return true;

		return false;
	}

	private function deleteOutput(&$factory, $text)
	{
		$outputs = $factory->getOutput();
		$output = intval($text);
		$limit = count($outputs);

		// Re-index in case we have a problem
		$outputs = array_values($outputs);

		// Take out the output value.
		for($i = 0; $i < $limit; $i ++)
		{
			if($output === $outputs[$i])
			{
				unset($outputs[$i]);
				break;
			}
		}

		// after our value has been unset, we reassign the new output
		$factory->setOutput($outputs);
	}

	// Delete factory output
	public function deleteFactoryOutputAction()
	{
		$content = $this->get('ncf.json_request')->getJson();
		
		if(!$this->validateUUID($content['factoryId']))
			return $this->badRequest();

		$dm = $this->get('doctrine_mongodb')->getManager();

		$factory = $this->get('doctrine_mongodb')
						->getRepository('NCarolinaFactoryBundle:Factory')
						->findOneById($content['factoryId']);

		if(!$factory)
			return $this->notFound();

		$this->deleteOutput($factory,$content['outputText']);

		$dm->persist($factory);
		$dm->flush();

		// Set view header 204, no content being sent back
		$view = $this->view(array(), 204);
		$view->setFormat('json');
		return $this->handleView($view);
	}

	// Delete node
	public function deleteNodeAction()
	{
		$content = $this->get('ncf.json_request')->getJson();

		if(!$this->validateUUID($content['id']))
			return $this->badRequest();

		$dm = $this->get('doctrine_mongodb')
					 ->getManager();

		switch($content['type'])
		{
			case 'ROOT':
				$node = $this->get('doctrine_mongodb')
							 ->getRepository('NCarolinaFactoryBundle:Root')
							 ->findOneById($content['id']);
				break;
			case 'FACTORY':
				$node = $this->get('doctrine_mongodb')
							 ->getRepository('NCarolinaFactoryBundle:Factory')
							 ->findOneById($content['id']);
				break;
			default:
				return $this->notFound();
		}

		// Remove our node
		$dm->remove($node);
		$dm->flush();

		// Set view header 204, no content being sent back
		$view = $this->view(array(), 204);
		$view->setFormat('json');
		return $this->handleView($view);
	}
}