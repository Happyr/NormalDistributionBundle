<?php

namespace Happyr\NormalDistributionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="HappyrDistributionFragment")
 * @ORM\Entity()
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Fragment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Summary summary
     *
     * @ORM\ManyToOne(targetEntity="Happyr\NormalDistributionBundle\Entity\Summary")
     */
    private $summary;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $value = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $frequency = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $cumulativeFrequency = 0;

    public function __construct(Summary $summary)
    {
        $this->summary = $summary;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSummary(Summary $summary)
    {
        $this->summary = $summary;
    }

    public function getSummary(): Summary
    {
        return $this->summary;
    }

    public function setValue(float $value)
    {
        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setCumulativeFrequency(int $cumulativeFrequency)
    {
        $this->cumulativeFrequency = $cumulativeFrequency;
    }

    public function getCumulativeFrequency(): int
    {
        return $this->cumulativeFrequency;
    }

    public function setFrequency(int $frequency)
    {
        $this->frequency = $frequency;
    }

    public function getFrequency(): int
    {
        return $this->frequency;
    }
}
