<?php

declare(strict_types=1);

namespace Happyr\NormalDistributionBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Happyr\NormalDistributionBundle\Entity\Fragment;
use Happyr\NormalDistributionBundle\Entity\Summary;

/**
 * This class handles distributions. It does not have to be a normal distribution.
 * Use this class when you have a normal distribution with an interval. Say that there are only
 * some values that are valid. Add the distribution to this service and we can give you the
 * correct percentile back.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class DistributionManager
{
    /**
     * @var EntityManagerInterface em
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Get the percentile for a distribution.
     *
     * @return int [1,100]
     */
    public function getPercentile(string $name, float $value): int
    {
        /* @var $lower \Happyr\NormalDistributionBundle\Entity\Fragment */
        /* @var $upper \Happyr\NormalDistributionBundle\Entity\Fragment */
        list($population, $lower, $upper) = $this->getFragments($name, $value);

        //make sure we have upper and lower limits
        if (null === $lower) {
            return 1;
        } elseif (null === $upper) {
            return 100;
        }

        /*
         * Make an linear interpolation between $upper and $lower to get the percentile
         */
        $x0 = $lower->getCumulativeFrequency();
        $y0 = $lower->getValue();
        $x1 = $upper->getCumulativeFrequency();
        $y1 = $upper->getValue();
        //$y=$value

        $x = $x0 + ($x1 - $x0) * ($value - $y0) / ($y1 - $y0);

        return (int) ceil(100 * $x / $population);
    }

    /**
     * Get the fragments that are around the value.
     *
     * @param $name
     * @param $value
     *
     * @return array ($population, $lower, $upper)
     */
    protected function getFragments(string $name, float $value): array
    {
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = $this->em->createQueryBuilder();

        $qb->select('f')
            ->addSelect('s.population')
            ->from('HappyrNormalDistributionBundle:Fragment', 'f')
            ->join('f.summary', 's')
            ->where('s.name = :name')
            ->setParameter('name', $name)
            ->setParameter('value', $value)
            ->setMaxResults(1);

        $qb2 = clone $qb;

        $qb->andWhere('f.value <= :value')
            ->orderBy('f.value', 'DESC');

        $qb2->andWhere('f.value > :value')
            ->orderBy('f.value', 'ASC');

        try {
            $result = $qb->getQuery()->getSingleResult();
            $lowerFragment = $result[0];
            $population = $result['population'];
        } catch (NoResultException $e) {
            $lowerFragment = null;
        }

        try {
            $result = $qb2->getQuery()->getSingleResult();
            $upperFragment = $result[0];
            $population = $result['population'];
        } catch (NoResultException $e) {
            $upperFragment = null;
        }

        if (!isset($population)) {
            throw new \InvalidArgumentException(sprintf('We could not find any distribution with name "%s"', $name));
        }

        return [$population, $lowerFragment, $upperFragment];
    }

    /**
     * Add a distribution.
     *
     * @param array &$values   must be of form array($value=>$frequency)
     * @param bool  $overwrite if true we overwrite a previous distribution with the same name
     */
    public function addDistribution(string $name, array $values, bool $overwrite = false): Summary
    {
        $fragments = [];
        $population = 0;

        //check if exists
        $summary = $this->em->getRepository('HappyrNormalDistributionBundle:Summary')->findOneByName($name);
        if (!$summary) {
            $summary = new Summary($name);
        } elseif (!$overwrite) {
            throw new \Exception(sprintf('A distribution with name "%s" does already exists.', $name));
        } else {
            //if we should overwrite, get all previous distribution entities
            $fragments = $this->em->getRepository('HappyrNormalDistributionBundle:Fragment')->findBy(['summary' => $summary->getId()]);
        }

        //sort the values
        ksort($values);

        foreach ($values as $value => $frequency) {
            $population += $frequency;

            //get an existing fragment if you can
            if (null === $fragment = array_shift($fragments)) {
                $fragment = new Fragment($summary);
            }

            $fragment->setCumulativeFrequency($population);
            $fragment->setFrequency((int) $frequency);
            $fragment->setValue((float) $value);

            $this->em->persist($fragment);
        }

        //remove the remaining fragments
        foreach ($fragments as $f) {
            $this->em->remove($f);
        }

        $summary->setPopulation($population);
        $this->em->persist($summary);

        return $summary;
    }

    /**
     * Create an array to use with addDistribution.
     *
     * @param array $values like array(3,5,6,1,6,2,7)
     *
     * @return array of form array($value=>$frequency)
     */
    public function createValueFrequencyArray(array $values): array
    {
        $result = [];

        foreach ($values as $v) {
            if (!isset($result["$v"])) {
                $result["$v"] = 0;
            }

            ++$result["$v"];
        }

        return $result;
    }
}
