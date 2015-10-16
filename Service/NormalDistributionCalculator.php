<?php

namespace Happyr\NormalDistributionBundle\Service;

/**
 * Class NormalDistributionService.
 *
 * @author Tobias Nyholm
 *
 * Calculate the normal distribution for a set of values.
 */
class NormalDistributionCalculator
{
    /**
     * Calculate the standard normal distribution
     * A standard normal distribution (or the unit normal distribution) is where
     * mean value=0 and the standard distribution=1.
     *
     * @param array &$values
     *
     * @return array $zValues
     */
    public function calculateStandardNormalDistribution(array &$values)
    {
        list($meanValue, $standardDeviation) = $this->calculateNormalDistribution($values);

        //calculate z transform values
        $zValues = array();
        foreach ($values as $v) {
            $zValues[] = ($v-$meanValue)/$standardDeviation;
        }

        return $zValues;
    }

    /**
     * Calculate the normal distribution of an array. This will return the "population standard deviation", not the sample standard deviation.
     *
     * @param array &$values
     * @param bool $sample should we caluclate the mean value of a sample? or the entire population?
     *
     * @return array ($meanValue, $standardDeviation, $variance, $populationCount)
     */
    public function calculateNormalDistribution(array &$values, $sample = true)
    {
        list($meanValue, $variance, $populationCount) = $this->getMeanValue($values, $sample);

        if ($populationCount <= 2) {
            return $this->tooSmallPopulation($values, $populationCount);
        }

        //we want to sum the squares of the diff for the mean value
        $sum = 0;
        foreach ($values as $v) {
            $sum += pow($meanValue-$v, 2);
        }

        //divide this sum by the populationCount and square root it
        $standardDeviation = sqrt($sum/($populationCount-($sample?1:0)));

        return array($meanValue, $standardDeviation, $variance, $populationCount);
    }

    /**
     * Get the mean value of the sample. This will return the "population variance" not the sample variance
     *
     * @param array &$values
     * @param bool $sample should we caluclate the mean value of a sample? or the entire population?
     *
     * @return array ($meanValue, $variance, $populationCount)
     */
    protected function getMeanValue(array &$values, $sample = true)
    {
        $count = count($values);
        if ($count <= 2) {
            return array(0, 0, $count);
        }

        $sum = array_sum($values);
        $mean = $sum / $count;
        $varianceSum = 0;

        foreach ($values as $v) {
            $varianceSum += pow($mean-$v,2);
        }

        $div = $sample ? 1 : 0;

        $variance = $varianceSum / ($count - $div);

        return array($mean, $variance, $count);
    }

    /**
     * Call this when population count is 0, 1 or 2.
     * This handles those special cases.
     *
     * @param array $values
     * @param int   $populationCount
     *
     * @return array ($meanValue, $standardDeviation, $variance, $populationCount)
     */
    protected function tooSmallPopulation(array $values, $populationCount)
    {
        if ($populationCount == 0) {
            return array(0, 0, 0, 0);
        }

        $sum = array_sum($values);

        return array($sum/$populationCount, 0, 0, 1);
    }
}
