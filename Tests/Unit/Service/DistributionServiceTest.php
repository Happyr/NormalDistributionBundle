<?php

namespace Happyr\NormalDistributionBundle\Tests\Unit\Service;

use Happyr\NormalDistributionBundle\Service\DistributionManager;
use PHPUnit\Framework\TestCase;

/**
 * Class DistributionServiceTest.
 *
 * @author Tobias Nyholm
 */
class DistributionServiceTest extends TestCase
{
    public function testGetPercentile()
    {
        $name = 'test';
        $value = 50;

        $lower = $this->getMockBuilder('Happyr\NormalDistributionBundle\Entity\Fragment')
            ->disableOriginalConstructor()->getMock();
        $lower->expects($this->once())->method('getCumulativeFrequency')->will($this->returnValue(40));
        $lower->expects($this->once())->method('getValue')->will($this->returnValue(12));

        $upper = $this->getMockBuilder('Happyr\NormalDistributionBundle\Entity\Fragment')
            ->disableOriginalConstructor()->getMock();
        $upper->expects($this->once())->method('getCumulativeFrequency')->will($this->returnValue(55));
        $upper->expects($this->once())->method('getValue')->will($this->returnValue(60));

        $service = $this->getMockBuilder('Happyr\NormalDistributionBundle\Service\DistributionManager')
            ->disableOriginalConstructor()
            ->setMethods(['getFragments'])
            ->getMock();
        $service->expects($this->once())
            ->method('getFragments')
            ->with($name, $value)
            ->will($this->returnValue([100, $lower, $upper]));

        $percentile = $service->getPercentile($name, $value);
        $this->assertEquals(52, $percentile);
    }

    /**
     * Test the ends of the distribution.
     */
    public function testGetPercentileEnds()
    {
        $name = 'test';
        $value = 50;

        $fragment = $this->getMockBuilder('Happyr\NormalDistributionBundle\Entity\Fragment')
            ->disableOriginalConstructor()->getMock();

        $service = $this->getMockBuilder('Happyr\NormalDistributionBundle\Service\DistributionManager')
            ->disableOriginalConstructor()
            ->setMethods(['getFragments'])
            ->getMock();
        $service->expects($this->once())
            ->method('getFragments')
            ->with($name, $value)
            ->will($this->returnValue([100, $fragment, null]));

        $this->assertEquals(100, $service->getPercentile($name, $value));

        $service = $this->getMockBuilder('Happyr\NormalDistributionBundle\Service\DistributionManager')
            ->disableOriginalConstructor()
            ->setMethods(['getFragments'])
            ->getMock();
        $service->expects($this->once())
            ->method('getFragments')
            ->with($name, $value)
            ->will($this->returnValue([100, null, $fragment]));

        $this->assertEquals(1, $service->getPercentile($name, $value));
    }

    public function testCreateValueFrequencyArray()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')
            ->disableOriginalConstructor()->getMock();
        $service = new DistributionManager($em);

        $param = [2, 5, 7, 2, 6, 7, 3, 4, 7, 27, 8, 3];
        $result = [
            2 => 2,
            5 => 1,
            7 => 3,
            6 => 1,
            3 => 2,
            4 => 1,
            8 => 1,
            27 => 1,
        ];

        $this->assertEquals($result, $service->createValueFrequencyArray($param));

        //empty test
        $param = [];
        $this->assertEquals([], $service->createValueFrequencyArray($param));
    }
}
