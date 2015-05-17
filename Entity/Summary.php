<?php


namespace Happyr\NormalDistributionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Summary
 *
 * @author Tobias Nyholm
 *
 * @ORM\Table(name="HappyRDistributionSummary")
 * @ORM\Entity()
 *
 */
class Summary
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
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $population;

    /**
     * @param string $name
     * @param int $population
     */
    public function __construct($name, $population=0)
    {
        $this->name = $name;
        $this->population = $population;
    }

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @param int $population
     *
     * @return $this
     */
    public function setPopulation($population)
    {
        $this->population = $population;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getPopulation()
    {
        return $this->population;
    }
}