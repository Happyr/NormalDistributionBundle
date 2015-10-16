<?php

namespace Happyr\NormalDistributionBundle\Tests\Service;

use Happyr\NormalDistributionBundle\Service\NormalDistributionCalculator;

/**
 * Class NormalDistributionServiceTest.
 *
 * http://www.calculator.net/standard-deviation-calculator.html
 *
 * @author Tobias Nyholm
 */
class NormalDistributionCalculatorTest extends \PHPUnit_Framework_TestCase
{
    public function testCalculateStandardNormalDistribution()
    {
        $param = array(4, 2, 1, 5, 8, 1, 7);
        $calculator = $this->getMock('Happyr\NormalDistributionBundle\Service\NormalDistributionCalculator', array('calculateNormalDistribution'));
        $calculator->expects($this->once())
            ->method('calculateNormalDistribution')
            ->with($param)
            ->will($this->returnValue(array(4, sqrt(8), 7, 7)));

        $result = $calculator->calculateStandardNormalDistribution($param);
        $this->assertEquals(array(
                0,
                -2/sqrt(8),
                -3/sqrt(8),
                1/sqrt(8),
                4/sqrt(8),
                -3/sqrt(8),
                3/sqrt(8),
            ), $result);
    }

    public function testCalculateNormalDistributionSample()
    {
        $calculator = new NormalDistributionCalculatorDummy();

        // Sample
        $input = array(4, 2, 1, 5, 8, 1, 7);
        list($meanValue, $standardDeviation, $variance, $populationCount) = $calculator->dummyNormalDistribution($input);
        $this->assertEquals(7, $populationCount);
        $this->assertEquals(8, $variance, '', 0.0001);
        $this->assertEquals(2.82842, $standardDeviation, '', 0.0001);
        $this->assertEquals(4, $meanValue);


        //Sample
        $input = array(4,6,3,7,8,2,8,9,5,4,2,6,3,1,3,6,8,9,5,3,4,6,7,8,4);
        list($meanValue, $standardDeviation, $variance, $populationCount) = $calculator->dummyNormalDistribution($input);
        $this->assertEquals(25, $populationCount);
        $this->assertEquals(5.52333, $variance, '', 0.0001);
        $this->assertEquals(2.35017, $standardDeviation, '', 0.0001);
        $this->assertEquals(5.24, $meanValue);
    }
    public function testCalculateNormalDistributionPopulation()
    {
        $calculator = new NormalDistributionCalculatorDummy();

        // Population
        $input = array(4, 2, 1, 5, 8, 1, 7);
        list($meanValue, $standardDeviation, $variance, $populationCount) = $calculator->dummyNormalDistribution($input, false);
        $this->assertEquals(7, $populationCount);
        $this->assertEquals(6.85714, $variance, '', 0.0001);
        $this->assertEquals(2.61861, $standardDeviation, '', 0.0001);
        $this->assertEquals(4, $meanValue);


        //Population
        $input = array(4,6,3,7,8,2,8,9,5,4,2,6,3,1,3,6,8,9,5,3,4,6,7,8,4);
        list($meanValue, $standardDeviation, $variance, $populationCount) = $calculator->dummyNormalDistribution($input, false);
        $this->assertEquals(25, $populationCount);
        $this->assertEquals(5.3024, $variance, '', 0.0001);
        $this->assertEquals(2.30269, $standardDeviation, '', 0.0001);
        $this->assertEquals(5.24, $meanValue);
    }

    public function testSmallCalculateNormalDistribution()
    {
        $param = array(4);
        $calculator = $this->getMock('Happyr\NormalDistributionBundle\Service\NormalDistributionCalculator', array('tooSmallPopulation'));
        $calculator->expects($this->once())
            ->method('tooSmallPopulation')
            ->with($param, count($param))
            ->will($this->returnValue(4711));

        $result = $calculator->calculateNormalDistribution($param);
        $this->assertEquals(4711, $result);

        $param = array();
        $calculator = $this->getMock('Happyr\NormalDistributionBundle\Service\NormalDistributionCalculator', array('tooSmallPopulation'));
        $calculator->expects($this->once())
            ->method('tooSmallPopulation')
            ->with($param, count($param))
            ->will($this->returnValue(4711));

        $result = $calculator->calculateNormalDistribution($param);
        $this->assertEquals(4711, $result);
    }

    public function testGetMeanValue()
    {
        $calculator = new NormalDistributionCalculatorDummy();

        // population
        $result = $calculator->dummyMeanValue(array(2, 5, 8, 4), false);
        $this->assertEquals(array(4.75, 4.6875, 4), $result);

        // sample
        $result = $calculator->dummyMeanValue(array(2, 5, 8, 4), true);
        $this->assertEquals(array(4.75, 6.25, 4), $result);

        $result = $calculator->dummyMeanValue(array(5, 5, 5));
        $this->assertEquals(array(5, 0, 3), $result);

        $result = $calculator->dummyMeanValue(array());
        $this->assertEquals(array(0, 0, 0), $result);
    }

    public function testTooSmallPopulation()
    {
        $calculator = new NormalDistributionCalculatorDummy();

        $result = $calculator->tooSmallPopulation(array(), 0);
        $this->assertEquals(array(0, 0, 0, 0), $result);

        $result = $calculator->tooSmallPopulation(array(5), 1);
        $this->assertEquals(array(5, 0, 0, 1), $result);
    }
}

class NormalDistributionCalculatorDummy extends NormalDistributionCalculator
{
    public function dummyNormalDistribution(array $values, $sample=true)
    {
        return parent::calculateNormalDistribution($values, $sample);
    }

    public function dummyMeanValue(array $values, $sample=true)
    {
        return parent::getMeanValue($values, $sample);
    }

    public function tooSmallPopulation(array $values, $populationCount)
    {
        return parent::tooSmallPopulation($values, $populationCount);
    }
}
