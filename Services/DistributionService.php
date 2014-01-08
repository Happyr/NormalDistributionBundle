<?php

namespace HappyR\NormalDistributionBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use HappyR\NormalDistributionBundle\Entity\Fragment;
use HappyR\NormalDistributionBundle\Entity\Summary;

/**
 * Class DistributionService
 *
 * @author Tobias Nyholm
 *
 * This class handles distributions. It does not have to be a normal distribution.
 * Use this class when you have a normal distribution with an interval. Say that there are only
 * some values that are valid. Add the distribution to this service and we can give you the
 * correct procentile back.
 *
 */
class DistributionService
{
    /**
     * @var \Doctrine\ORM\EntityManager em
     *
     */
    protected $em;

    /**
     * @param ObjectManager $em
     */
    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Get the procentile for a distribution
     *
     *
     * @param string $name
     * @param float $value
     *
     * @return int [1,100]
     */
    public function getPercentile($name, $value)
    {
        /* @var $lower \HappyR\NormalDistributionBundle\Entity\Fragment */
        /* @var $upper \HappyR\NormalDistributionBundle\Entity\Fragment */
        list($population, $lower, $upper)=$this->getFragments($name, $value);

        //make sure we have upper and lower limits
        if ($lower==null) {
            return 1;
        } elseif ($upper==null) {
            return 100;
        }

        /*
         * Make an linear interpolation between $upper and $lower to get the percentile
         */
        $x0=$lower->getCumulativeFrequency();
        $y0=$lower->getValue();
        $x1=$upper->getCumulativeFrequency();
        $y1=$upper->getValue();
        //$y=$value

        $x=$x0+($x1-$x0)*($value-$y0)/($y1-$y0);

        return ceil(100*$x/$population);
    }

    /**
     * Get the fragments that are around the value
     *
     * @param $name
     * @param $value
     *
     * @return array ($population, $lower, $upper)
     */
    protected function getFragments($name, $value)
    {
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb=$this->em->createQueryBuilder();

        $qb->select('f')
            ->addSelect('s.population')
            ->from('HappyRNormalDistributionBundle:Fragment', 'f')
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

        try{
            $result=$qb->getQuery()->getSingleResult();
            $lowerFragment=$result[0];
            $max=$result['population'];
        } catch (NoResultException $e) {
            $lowerFragment = null;
        }

        try{
            $result=$qb2->getQuery()->getSingleResult();
            $upperFragment=$result[0];
            $max=$result['population'];
        } catch (NoResultException $e) {
            $upperFragment = null;
        }

        return array($max, $lowerFragment, $upperFragment);
    }

    /**
     * Add a distribution
     *
     * @param string $name
     * @param array &$values must be of form array($value=>$frequency)
     * @param boolean $overwrite if true we overwrite a previous distribution with the same name
     *
     */
    public function addDistribution($name, array $values, $overwrite=false)
    {
        $fragments=array();
        $population=0;

        //check if exists
        $summary=$this->em->getRepository('HappyRNormalDistributionBundle:Summary')->findOneByName($name);
        if (!$summary) {
            $summary=new Summary($name);
        } elseif (!$overwrite) {
            throw new \Exception(sprintf('A distribution with name "%s" does already exists.', $name));
        } else {
            //if we should overwrite, get all previous distribution entities
            $fragments=$this->em->getRepository('HappyRNormalDistributionBundle:Fragment')->findBy(array('summary'=>$summary->getId()));
        }

        //sort the values
        ksort($values);

        foreach ($values as $value=>$frequency) {
            $population+=$frequency;

            //get an existing fragment if you can
            if(null === $fragment=array_shift($fragments)) {
                $fragment=new Fragment($summary);
            }

            $fragment
                ->setCumulativeFrequency($population)
                ->setFrequency($frequency)
                ->setValue($value);

            $this->em->persist($fragment);
        }

        //remove the remaining fragments
        foreach ($fragments as $f) {
            $this->em->remove($f);
        }

        $summary->setPopulation($population);
        $this->em->persist($summary);

        $this->em->flush();
    }

    /**
     * Create an array to use with addDistribution.
     *
     * @param array &$values like array(3,5,6,1,6,2,7)
     *
     * @return array of form array($value=>$frequency)
     */
    public function createValueFrequencyArray(array &$values)
    {
        $result=array();

        foreach ($values as $v) {
            if (!isset($result[$v])) {
                $result[$v]=0;
            }

            $result[$v]++;
        }

        return $result;
    }
}