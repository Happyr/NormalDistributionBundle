<?php

namespace Happyr\NormalDistributionBundle\Service;

/**
 * Get some values from a already calculated normal distribution.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class StatisticsHelper
{
    /**
     * Get the percentile for a specific value.
     *
     * @param float $value
     * @param float $meanValue
     * @param float $standardDeviation
     *
     * @return int [0,100]
     */
    public function getPercentile(float $value, float $meanValue = 0, float $standardDeviation = 1): int
    {
        $z = $this->getZTransform($value, $meanValue, $standardDeviation);

        $b1 = 0.319381530;
        $b2 = -0.356563782;
        $b3 = 1.781477937;
        $b4 = -1.821255978;
        $b5 = 1.330274429;
        $p = 0.2316419;
        $c = 0.39894228;

        /* I choose not to comment this because I want to give future me an headache. //Tobias */
        if ($z >= 0.0) {
            $t = 1.0 / (1.0 + $p * $z);

            $percentile = (1.0 - $c * exp(-$z * $z / 2.0) * $t *
                ($t * ($t * ($t * ($t * $b5 + $b4) + $b3) + $b2) + $b1));
        } else {
            $t = 1.0 / (1.0 - $p * $z);

            $percentile = ($c * exp(-$z * $z / 2.0) * $t *
                ($t * ($t * ($t * ($t * $b5 + $b4) + $b3) + $b2) + $b1));
        }

        return ceil($percentile * 100);
    }

    /**
     * This will return the corresponding value in a standard normal distribution.
     *
     * @param float $value
     * @param float $meanValue
     * @param float $standardDeviation
     *
     * @return float
     */
    public function getZTransform(float $value, float $meanValue, float $standardDeviation): float
    {
        return ($value - $meanValue) / $standardDeviation;
    }

    /**
     * Get the stanine value
     * http://en.wikipedia.org/wiki/Stanine.
     *
     * @param float $value
     * @param float $meanValue
     * @param float $standardDeviation
     *
     * @return int [1,9]
     */
    public function getStanine(float $value, float $meanValue = 0, float $standardDeviation = 1): int
    {
        //$bound is now the lower limit of stanine=2
        $bound = $meanValue - (1.75 * $standardDeviation);
        $change = 0.5 * $standardDeviation;

        for ($i = 1; $i < 9; $i++, $bound += $change) {
            if ($value < $bound) {
                return $i;
            }
        }

        return 9;
    }

    /**
     * Return the stanine slot for given percentage
     * http://en.wikipedia.org/wiki/Stanine.
     *
     * @param float $percentile [0-100]
     *
     * @return int [1,9]
     */
    public function getStanineForPercentile(float $percentile): int
    {
        //an array with boundaries. These must be in ascending order
        $limits = [4, 11, 23, 40, 60, 77, 89, 96];

        //for each limit
        foreach ($limits as $key => $limit) {
            //if the percentile is smaller than the limit
            if ($percentile < $limit) {
                //the slot is $key+1
                return $key + 1;
            }
        }

        return 9;
    }
}
