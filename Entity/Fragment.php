<?php

namespace Happyr\NormalDistributionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="HappyrDistributionFragment")
 * @ORM\Entity()

 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Fragment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Summary summary
     *
     * @ORM\ManyToOne(targetEntity="Happyr\NormalDistributionBundle\Entity\Summary")
     */
    protected $summary;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $value;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $frequency;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $cumulativeFrequency;

    /**
     * @param Summary $summary
     */
    public function __construct(Summary $summary)
    {
        $this->summary = $summary;
    }

    /**
     * @param int $cumulativeFrequency
     *
     * @return $this
     */
    public function setCumulativeFrequency($cumulativeFrequency)
    {
        $this->cumulativeFrequency = $cumulativeFrequency;

        return $this;
    }

    /**
     * @return int
     */
    public function getCumulativeFrequency()
    {
        return $this->cumulativeFrequency;
    }

    /**
     * @param int $frequency
     *
     * @return $this
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * @return int
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Happyr\NormalDistributionBundle\Entity\Summary $summary
     *
     * @return $this
     */
    public function setSummary(Summary $summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return \Happyr\NormalDistributionBundle\Entity\Summary
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param float $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }
}
