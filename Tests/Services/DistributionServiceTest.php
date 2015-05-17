<?php

namespace Happyr\NormalDistributionBundle\Tests\Services;

use Happyr\NormalDistributionBundle\Entity\Fragment;
use Happyr\NormalDistributionBundle\Service\DistributionService;

/**
 * Class DistributionServiceTest.
 *
 * @author Tobias Nyholm
 */
class DistributionServiceTest extends \PHPUnit_Framework_TestCase
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
            ->disableOriginalConstructor()->getMock('Happyr\NormalDistributionBundle\Entity\Fragment');
        $upper->expects($this->once())->method('getCumulativeFrequency')->will($this->returnValue(55));
        $upper->expects($this->once())->method('getValue')->will($this->returnValue(60));

        $service = $this->getMock('Happyr\NormalDistributionBundle\Service\DistributionService', array('getFragments'), array(), '', false);
        $service->expects($this->once())
            ->method('getFragments')
            ->with($name, $value)
            ->will($this->returnValue(array(100, $lower, $upper)));

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

        $service = $this->getMock('Happyr\NormalDistributionBundle\Service\DistributionService', array('getFragments'), array(), '', false);
        $service->expects($this->once())
            ->method('getFragments')
            ->with($name, $value)
            ->will($this->returnValue(array(100, $fragment, null)));

        $this->assertEquals(100, $service->getPercentile($name, $value));

        $service = $this->getMock('Happyr\NormalDistributionBundle\Service\DistributionService', array('getFragments'), array(), '', false);
        $service->expects($this->once())
            ->method('getFragments')
            ->with($name, $value)
            ->will($this->returnValue(array(100, null, $fragment)));

        $this->assertEquals(1, $service->getPercentile($name, $value));
    }

    public function testCreateValueFrequencyArray()
    {
        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()->getMock();
        $service = new DistributionService($em);

        $param = array(2,5,7,2,6,7,3,4,7,27,8,3);
        $result = array(
            2 => 2,
            5 => 1,
            7 => 3,
            6 => 1,
            3 => 2,
            4 => 1,
            8 => 1,
            27 => 1,
        );

        $this->assertEquals($result, $service->createValueFrequencyArray($param));

        //empty test
        $param = array();
        $this->assertEquals(array(), $service->createValueFrequencyArray($param));
    }

    public function testSortValues()
    {
        $service = new DummyDistributionService();

        $input = array(
            4 => 'B',
            3 => 'O',
            5 => 'A',
            1 => 'F',
            9 => 'R',
            2 => 'O',
        );

        $expected = array(
            1 => 'F',
            2 => 'O',
            3 => 'O',
            4 => 'B',
            5 => 'A',
            9 => 'R',
        );

        $service->sortValues($input);
        $this->assertEquals($expected, $input);

        $input = array(
        0 => 'B',
        -3 => 'O',
        5 => 'A',
        -31 => 'F',
        9 => 'R',
        -10 => 'O',
    );

        $expected = array(
            -31 => 'F',
            -10 => 'O',
            -3 => 'O',
            0 => 'B',
            5 => 'A',
            9 => 'R',
        );

        $service->sortValues($input);
        $this->assertEquals($expected, $input, 'Sort with negative keys');
    }

    /**
     * Try to sort with strings as keys.
     */
    public function testSortValuesWithStrings()
    {
        $service = new DummyDistributionService();

        $input = array(
            '0' => 'B',
            '-3' => 'O',
            '5' => 'A',
            '-31' => 'F',
            '9' => 'R',
            '-10' => 'O',
        );

        $expected = array(
            '-31' => 'F',
            '-10' => 'O',
            '-3' => 'O',
            '0' => 'B',
            '5' => 'A',
            '9' => 'R',
        );

        $service->sortValues($input);
        $this->assertEquals($expected, $input, 'Sort with negative values with strings as keys');
    }

    /**
     * Try to sort with strings as keys.
     */
    public function testSortValuesWithFloats()
    {
        $service = new DummyDistributionService();

        $input = array(
            '0.6' => 'B',
            '0.01' => 'O',
            '1.1' => 'A',
            '0.0' => 'F',
            '2' => 'R',
            '0.512' => 'O',
        );

        $expected = array(
            '0.0' => 'F',
            '0.01' => 'O',
            '0.512' => 'O',
            '0.6' => 'B',
            '1.1' => 'A',
            '2' => 'R',
        );

        $service->sortValues($input);
        $this->assertEquals($expected, $input, 'Sort with float as keys');
    }
}

class DummyDistributionService extends DistributionService
{
    public function __construct($entityManager = null)
    {
        if ($entityManager) {
            parent::__construct($entityManager);
        }
    }

    public function getFragments($name, $value)
    {
        return parent::getFragments($name, $value);
    }

    public function sortValues(array &$values)
    {
        parent::sortValues($values);
    }
}
