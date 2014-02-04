<?php

namespace NCarolina\FactoryBundle\Document;



/**
 * NCarolina\FactoryBundle\Document\Root
 */
class Root
{
    /**
     * @var $id
     */
    protected $id;

    /**
     * @var string $type
     */
    protected $type;

    /**
     * @var string $text
     */
    protected $text;

    /**
     * @var object
     */
    protected $children = array();

    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return custom_id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Get text
     *
     * @return string $text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Add child
     *
     * @param $child
     */
    public function addChild($child)
    {
        $this->children[] = $child;
    }

    /**
     * Remove child
     *
     * @param $child
     */
    public function removeChild($child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection $children
     */
    public function getChildren()
    {
        return $this->children;
    }
}
