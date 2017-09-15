<?php

namespace Happyr\NormalDistributionBundle\Tests\Unit\Service;

use Happyr\NormalDistributionBundle\Service\NormalDistributionCalculator;
use Nyholm\NSA;
use PHPUnit\Framework\TestCase;

/**
 * Class NormalDistributionServiceTest.
 *
 * http://www.calculator.net/standard-deviation-calculator.html
 *
 * @author Tobias Nyholm
 */
class NormalDistributionCalculatorTest extends TestCase
{
    public function testCalculateStandardNormalDistribution()
    {
        $param = [4, 2, 1, 5, 8, 1, 7];
        $calculator = $this->getMockBuilder('Happyr\NormalDistributionBundle\Service\NormalDistributionCalculator')
            ->setMethods(['calculateNormalDistribution'])
            ->getMock();
        $calculator->expects($this->once())
            ->method('calculateNormalDistribution')
            ->with($param)
            ->will($this->returnValue([4, sqrt(8), 7, 7]));

        $result = $calculator->calculateStandardNormalDistribution($param);
        $this->assertEquals([
                0,
                -2 / sqrt(8),
                -3 / sqrt(8),
                1 / sqrt(8),
                4 / sqrt(8),
                -3 / sqrt(8),
                3 / sqrt(8),
            ], $result);
    }

    public function testCalculateNormalDistributionSample()
    {
        $calculator = new NormalDistributionCalculator();

        // Sample
        $input = [4, 2, 1, 5, 8, 1, 7];
        list($meanValue, $standardDeviation, $variance, $populationCount) = $calculator->calculateNormalDistribution($input);
        $this->assertEquals(7, $populationCount);
        $this->assertEquals(8, $variance, '', 0.0001);
        $this->assertEquals(2.82842, $standardDeviation, '', 0.0001);
        $this->assertEquals(4, $meanValue);

        //Sample
        $input = [4, 6, 3, 7, 8, 2, 8, 9, 5, 4, 2, 6, 3, 1, 3, 6, 8, 9, 5, 3, 4, 6, 7, 8, 4];
        list($meanValue, $standardDeviation, $variance, $populationCount) = $calculator->calculateNormalDistribution($input);
        $this->assertEquals(25, $populationCount);
        $this->assertEquals(5.52333, $variance, '', 0.0001);
        $this->assertEquals(2.35017, $standardDeviation, '', 0.0001);
        $this->assertEquals(5.24, $meanValue);
    }

    public function testCalculateNormalDistributionPopulation()
    {
        $calculator = new NormalDistributionCalculator();

        // Population
        $input = [4, 2, 1, 5, 8, 1, 7];
        list($meanValue, $standardDeviation, $variance, $populationCount) = $calculator->calculateNormalDistribution($input, false);
        $this->assertEquals(7, $populationCount);
        $this->assertEquals(6.85714, $variance, '', 0.0001);
        $this->assertEquals(2.61861, $standardDeviation, '', 0.0001);
        $this->assertEquals(4, $meanValue);

        //Population
        $input = [4, 6, 3, 7, 8, 2, 8, 9, 5, 4, 2, 6, 3, 1, 3, 6, 8, 9, 5, 3, 4, 6, 7, 8, 4];
        list($meanValue, $standardDeviation, $variance, $populationCount) = $calculator->calculateNormalDistribution($input, false);
        $this->assertEquals(25, $populationCount);
        $this->assertEquals(5.3024, $variance, '', 0.0001);
        $this->assertEquals(2.30269, $standardDeviation, '', 0.0001);
        $this->assertEquals(5.24, $meanValue);
    }

    public function testSmallCalculateNormalDistribution()
    {
        $value = [4711];
        $param = [4];
        $calculator = $this->getMockBuilder('Happyr\NormalDistributionBundle\Service\NormalDistributionCalculator')
            ->setMethods(['tooSmallPopulation'])
            ->getMock();
        $calculator->expects($this->once())
            ->method('tooSmallPopulation')
            ->with($param, count($param))
            ->willReturn($value);

        $result = $calculator->calculateNormalDistribution($param);
        $this->assertEquals($value, $result);

        $param = [];
        $calculator = $this->getMockBuilder('Happyr\NormalDistributionBundle\Service\NormalDistributionCalculator')
            ->setMethods(['tooSmallPopulation'])
            ->getMock();
        $calculator->expects($this->once())
            ->method('tooSmallPopulation')
            ->with($param, count($param))
            ->willReturn($value);

        /** @var NormalDistributionCalculator $calculator */
        $result = $calculator->calculateNormalDistribution($param);
        $this->assertEquals($value, $result);
    }

    public function testGetMeanValue()
    {
        $calculator = new NormalDistributionCalculator();

        // population
        $result = NSA::invokeMethod($calculator, 'getMeanValue', [2, 5, 8, 4], false);
        $this->assertEquals([4.75, 4.6875, 4], $result);

        // sample
        $result = NSA::invokeMethod($calculator, 'getMeanValue', [2, 5, 8, 4], true);
        $this->assertEquals([4.75, 6.25, 4], $result);

        $result = NSA::invokeMethod($calculator, 'getMeanValue', [5, 5, 5]);
        $this->assertEquals([5, 0, 3], $result);

        $result = NSA::invokeMethod($calculator, 'getMeanValue', []);
        $this->assertEquals([0, 0, 0], $result);
    }

    public function testTooSmallPopulation()
    {
        $calculator = new NormalDistributionCalculator();

        $result = NSA::invokeMethod($calculator, 'tooSmallPopulation', [], 0);
        $this->assertEquals([0, 0, 0, 0], $result);

        $result = NSA::invokeMethod($calculator, 'tooSmallPopulation', [5], 1);
        $this->assertEquals([5, 0, 0, 1], $result);
    }
}
