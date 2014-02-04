<?php

namespace NCarolina\FactoryBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use NCarolina\FactoryBundle\Document\Root;
use NCarolina\FactoryBundle\Document\Factory;

class LoadRootData implements FixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $dm)
	{
		$root = new Root();

		$root->setText("Taylor Root");
		$root->setType("ROOT");

		$factory = new Factory();
		$factory->setText("Taylor Factory");
		$factory->setLowerBound(0);
		$factory->setUpperBound(100);
		$factory->setPool(range(0,100));

		$factory->setParent($root);
		$root->addChild($factory);

		$dm->persist($root);
		$dm->persist($factory);
		
		$dm->flush();
	}
}