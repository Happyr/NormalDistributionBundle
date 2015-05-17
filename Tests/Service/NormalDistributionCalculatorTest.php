<?php

namespace Happyr\NormalDistributionBundle\Tests\Service;

use Happyr\NormalDistributionBundle\Service\NormalDistributionCalculator;

/**
 * Class NormalDistributionServiceTest.
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

    public function testCalculateNormalDistribution()
    {
        $calculator = new NormalDistributionCalculatorDummy();

        $result = $calculator->dummyNormalDistribution(array(4, 2, 1, 5, 8, 1, 7));
        $this->assertEquals(array(4, sqrt(8), 7, 7), $result);
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

        $result = $calculator->dummyMeanValue(array(2, 5, 8, 4));
        $this->assertEquals(array(4.75, 6, 4), $result);

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
