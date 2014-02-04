<?php

namespace NCarolina\FactoryBundle\Services;

use Symfony\Component\DependencyInjection\Container;

class JSONRequest
{

	protected $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	// Return associative array of information
	public function getJson()
	{
		return json_decode($this->container->get('request')->getContent(),true);
	}
}