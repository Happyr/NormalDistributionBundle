<?php


namespace HappyR\NormalDistributionBundle\Tests\Services;

use HappyR\NormalDistributionBundle\Services\NormalDistributionCalculator;

/**
 * Class NormalDistributionServiceTest
 *
 * @author Tobias Nyholm
 *
 */
class NormalDistributionCalculatorTest extends \PHPUnit_Framework_TestCase
{

    public function testCalculateNormalDistribution()
    {
        $calculator=new NormalDistributionCalculatorDummy();

        $result=$calculator->dummyNormalDistribution(array(4, 2, 1, 5, 8, 1, 7));
        $this->assertEquals(array(4, sqrt(8), 7, 7), $result);
    }

    public function testGetMeanValue()
    {
        $calculator=new NormalDistributionCalculatorDummy();

        $result=$calculator->dummyMeanValue(array(2,5,8,4));
        $this->assertEquals(array(4.75,6,4), $result);

        $result=$calculator->dummyMeanValue(array(5,5,5));
        $this->assertEquals(array(5,0,3), $result);
    }


    public function testTooSmallPopulation()
    {
        $calculator=new NormalDistributionCalculatorDummy();

        $result=$calculator->tooSmallPopulation(array(), 0);
        $this->assertEquals(array(0,0,0,0), $result);

        $result=$calculator->tooSmallPopulation(array(5), 1);
        $this->assertEquals(array(5,0,0,1), $result);
    }
}

class NormalDistributionCalculatorDummy extends NormalDistributionCalculator
{
    public function dummyNormalDistribution(array $values)
    {
        return parent::calculateNormalDistribution($values);
    }

    public function dummyMeanValue(array $values)
    {
        return parent::getMeanValue($values);
    }

    public function tooSmallPopulation(array $values, $populationCount)
    {
        return parent::tooSmallPopulation($values, $populationCount);
    }
}