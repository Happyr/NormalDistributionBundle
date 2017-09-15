<?php

namespace Happyr\NormalDistributionBundle\Service;

/**
 * Calculate the normal distribution for a set of values.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class Calculator
{
    /**
     * Calculate the standard normal distribution
     * A standard normal distribution (or the unit normal distribution) is where
     * meanValue=0 and the standardDistribution=1.
     *
     * @param array $values
     *
     * @return array $zValues
     */
    public function standardDistribution(array $values): array
    {
        list($meanValue, $standardDeviation) = $this->normalDistribution($values);

        //calculate z transform values
        $zValues = [];
        foreach ($values as $v) {
            $zValues[] = ($v - $meanValue) / $standardDeviation;
        }

        return $zValues;
    }

    /**
     * Calculate the normal distribution of an array.
     *
     * @param array $values
     * @param bool  $sample should we calculate the mean value of a sample or the entire population?
     *
     * @return array ($meanValue, $standardDeviation, $variance, $populationCount)
     */
    public function normalDistribution(array $values, bool $sample = true): array
    {
        list($meanValue, $variance, $populationCount) = $this->getMeanValue($values, $sample);

        if ($populationCount <= 2) {
            return $this->tooSmallPopulation($values, $populationCount);
        }

        //we want to sum the squares of the diff for the mean value
        $sum = 0;
        foreach ($values as $v) {
            $sum += pow($meanValue - $v, 2);
        }

        //divide this sum by the populationCount and square root it
        $standardDeviation = sqrt($sum / ($populationCount - ($sample ? 1 : 0)));

        return [$meanValue, $standardDeviation, $variance, $populationCount];
    }

    /**
     * Get the mean value of the sample.
     *
     * @param array $values
     * @param bool  $sample should we calculate the mean value of a sample or the entire population?
     *
     * @return array ($meanValue, $variance, $populationCount)
     */
    private function getMeanValue(array $values, bool $sample = true): array
    {
        $count = count($values);
        if ($count <= 2) {
            return [0, 0, $count];
        }

        $sum = array_sum($values);
        $mean = $sum / $count;
        $varianceSum = 0;

        foreach ($values as $v) {
            $varianceSum += pow($mean - $v, 2);
        }

        $div = $sample ? 1 : 0;

        $variance = $varianceSum / ($count - $div);

        return [$mean, $variance, $count];
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
    protected function tooSmallPopulation(array $values, int $populationCount): array
    {
        if ($populationCount == 0) {
            return [0, 0, 0, 0];
        }

        $sum = array_sum($values);

        return [$sum / $populationCount, 0, 0, 1];
    }
}
