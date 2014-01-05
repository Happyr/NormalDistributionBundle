<?php


namespace HappyR\NormalDistributionBundle\Services;


/**
 * Class StatisticsService
 *
 * @author Tobias Nyholm
 *
 */
class StatisticsService
{
    public function getProcentile($meanValue, $standardDeviation, $value)
    {

    }

    /**
     * Get the stanine value
     * http://en.wikipedia.org/wiki/Stanine
     *
     * @param $meanValue
     * @param $standardDeviation
     * @param $value
     *
     * return int [1,9]
     */
    public function getStanine($meanValue, $standardDeviation, $value)
    {
        //$bound is now the lower limit of stanine=2
        $bound=$meanValue-(1.75*$standardDeviation);
        $change=0.5*$standardDeviation;


        for ($i=1; $i<9; $i++, $bound+=$change) {
            if ($value<$bound) {
                return $i;
            }
        }
        
        return 9;
    }

    /**
     * Return the stanine slot for given percentage
     * http://en.wikipedia.org/wiki/Stanine
     *
     * @param int $percentile
     *
     * @return int
     */
    public function getStanineForProcentile($percentile)
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

        //return max value
        return 9;
    }
} 