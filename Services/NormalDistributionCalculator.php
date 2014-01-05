<?php


namespace HappyR\NormalDistributionBundle\Services;


/**
 * Class NormalDistributionService
 *
 * @author Tobias Nyholm
 *
 * Calculate the normal distrbution for a set of values where the values are unbounded
 */
class NormalDistributionCalculator
{

    /**
     * Calculate the standard normal distribution
     * A standard normal distribution (or the unit normal distribution) is where
     * mean value=0 and the standard distribution=1
     *
     * @param array $values
     *
     */
    public function calculateStandardNormalDistribution(array &$values)
    {
        list($meanValue, $standardDeviation, $variance, $populationCount)=$this->calculateNormalDistribution($values);

        //calculate z transform values
        $zValues=array();
        foreach ($values as $v) {
            $zValues[]=($v-$meanValue)/$standardDeviation;
        }

        return $zValues;
    }

    /**
     * Calculate the normal distibution of an array
     *
     * @param array &$values
     *
     * @return array ($meanValue, $standardDeviation, $variance, $populationCount)
     */
    public function calculateNormalDistribution(array &$values)
    {
        list($meanValue, $variance, $populationCount) = $this->getMeanValue($values);

        if ($populationCount <= 1) {
            return $this->tooSmallPopulation($values, $populationCount);
        }


        //we want to sum the squars of the diff for the mean value
        $sum=0;
        foreach ($values as $v) {
            $sum+=pow($meanValue-$v,2);
        }

        //divide this sum by the populationCount-1 and square root it
        $standardDeviation=sqrt($sum/($populationCount-1));


        return array($meanValue, $standardDeviation, $variance, $populationCount);
    }

    /**
     * Get the mean value
     *
     * @param array $values
     *
     * @return array ($meanValue, $variance, $populationCount)
     */
    protected function getMeanValue(array &$values)
    {
        $count=count($values);
        if ($count==0) {
            return array(0, 0, 0);
        }

        $high=null;
        $low=null;
        $sum=0;

        foreach ($values as $v) {
            if ($v>$high || $high==null) {
                $high=$v;
            }

            if ($v<$low || $low==null) {
                $low=$v;
            }

            $sum+=$v;
        }

        return array($sum/$count, $high-$low, $count);
    }

    /**
     * Call this whem population count is 0 or 1.
     * This handles those special cases
     *
     * @param array $values
     * @param $populationCount
     *
     * @return array
     */
    protected function tooSmallPopulation(array $values, $populationCount)
    {
        if  ($populationCount==0) {
            return array(0, 0, 0, 0);
        }

        return array(array_shift($values), 0, 0, 1);
    }
} 