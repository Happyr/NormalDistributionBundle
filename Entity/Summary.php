<?php

namespace Happyr\NormalDistributionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="HappyrDistributionSummary")
 * @ORM\Entity()
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Summary
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
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $population;

    /**
     * @param string $name
     * @param int    $population
     */
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
