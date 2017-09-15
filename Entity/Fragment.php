<?php

namespace Happyr\NormalDistributionBundle\Entity;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Fragment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Summary summary
     */
    private $summary;

    /**
     * @var float
     */
    private $value = 0;

    /**
     * @var int
     */
    private $frequency = 0;

    /**
     * @var int
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
