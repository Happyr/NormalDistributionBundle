<?php

namespace Happyr\NormalDistributionBundle\Service;

/**
 * Class StatisticsService
 *
 * @author Tobias Nyholm
 *
 * Get some values from a already calculated normal distribution
 *
 */
class StatisticsService
{
    /**
     * Get the percentile for a curtain value
     *
     * @param float $value
     * @param float $meanValue
     * @param float $standardDeviation
     *
     * @return int [0,100]
     */
    public function getPercentile($value, $meanValue = 0, $standardDeviation = 1)
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

            $percentile= (1.0 - $c * exp(-$z * $z / 2.0) * $t *
                ($t * ($t * ($t * ($t * $b5 + $b4) + $b3) + $b2) + $b1));
        } else {
            $t = 1.0 / (1.0 - $p * $z);

            $percentile= ($c * exp(-$z * $z / 2.0) * $t *
                ($t * ($t * ($t * ($t * $b5 + $b4) + $b3) + $b2) + $b1));
        }

        return ceil($percentile*100);
    }

    /**
     * This will return the corresponding value in a standard normal distribution
     *
     * @param float $value
     * @param float $meanValue
     * @param float $standardDeviation
     *
     * @return float
     */
    public function getZTransform($value, $meanValue, $standardDeviation)
    {
        return ($value - $meanValue) / $standardDeviation;
    }

    /**
     * Get the stanine value
     * http://en.wikipedia.org/wiki/Stanine
     *
     * @param float $value
     * @param float $meanValue
     * @param float $standardDeviation
     *
     * @return int [1,9]
     */
    public function getStanine($value, $meanValue = 0, $standardDeviation = 1)
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
     * http://en.wikipedia.org/wiki/Stanine
     *
     * @param float $percentile [0-100]
     *
     * @return int [1,9]
     */
    public function getStanineForPercentile($percentile)
    {
        //an array with boundaries. These must be in ascending order
        $limits = array(4, 11, 23, 40, 60, 77, 89, 96);

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