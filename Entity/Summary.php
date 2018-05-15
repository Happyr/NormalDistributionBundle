<?php

declare(strict_types=1);

namespace Happyr\NormalDistributionBundle\Entity;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Summary
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $population;

    public function __construct(string $name, int $population = 0)
    {
        $this->name = $name;
        $this->population = $population;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $population
     */
    public function setPopulation(int $population)
    {
        $this->population = $population;
    }

    /**
     * @return int
     */
    public function getPopulation(): int
    {
        return $this->population;
    }
}
