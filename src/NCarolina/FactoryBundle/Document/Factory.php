<?php

namespace NCarolina\FactoryBundle\Document;



/**
 * NCarolina\FactoryBundle\Document\Factory
 */
class Factory
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
     * @var int $lowerBound
     */
    protected $lowerBound;

    /**
     * @var int $upperBound
     */
    protected $upperBound;

    /**
     * @var hash $pool
     */
    protected $pool;

    /**
     * @var hash $output
     */
    protected $output;

    /**
     * @var object
     */
    protected $parent;


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
     * Set lowerBound
     *
     * @param int $lowerBound
     * @return self
     */
    public function setLowerBound($lowerBound)
    {
        $this->lowerBound = $lowerBound;
        return $this;
    }

    /**
     * Get lowerBound
     *
     * @return int $lowerBound
     */
    public function getLowerBound()
    {
        return $this->lowerBound;
    }

    /**
     * Set upperBound
     *
     * @param int $upperBound
     * @return self
     */
    public function setUpperBound($upperBound)
    {
        $this->upperBound = $upperBound;
        return $this;
    }

    /**
     * Get upperBound
     *
     * @return int $upperBound
     */
    public function getUpperBound()
    {
        return $this->upperBound;
    }

    /**
     * Set pool
     *
     * @param hash $pool
     * @return self
     */
    public function setPool($pool)
    {
        $this->pool = $pool;
        return $this;
    }

    /**
     * Get pool
     *
     * @return hash $pool
     */
    public function getPool()
    {
        return $this->pool;
    }

    /**
     * Set output
     *
     * @param hash $output
     * @return self
     */
    public function setOutput($output)
    {
        $this->output = $output;
        return $this;
    }

    /**
     * Get output
     *
     * @return hash $output
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set parent
     *
     * @param $parent
     * @return self
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return $parent
     */
    public function getParent()
    {
        return $this->parent;
    }
}
