<?php


namespace Happyr\NormalDistributionBundle\Tests\Services;

use Happyr\NormalDistributionBundle\Service\StatisticsService;

/**
 * Class StatisticsServiceTest
 *
 * @author Tobias Nyholm
 *
 */
class StatisticsServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPercentile()
    {
        $service=new StatisticsService();

        //Yes, this is a weird test.. I compared the values from http://stattrek.com/online-calculator/normal.aspx
        $this->assertEquals(79, $service->getPercentile(0.8));
        $this->assertEquals(54, $service->getPercentile(0.1));
        $this->assertEquals(35, $service->getPercentile(-0.4));
        $this->assertEquals(0, $service->getPercentile(-100));
        $this->assertEquals(100, $service->getPercentile(100));
    }

    public function testGetStanine()
    {
        $service=new StatisticsService();

        for ($i=1; $i<=9; $i++) {
            $this->assertEquals($i, $service->getStanine(12+$i, 17, 2));
        }
    }

    public function testgetStanineForPercentile()
    {
        $service = new StatisticsService();

        $this->assertEquals(1, $service->getStanineForPercentile(-2));
        $this->assertEquals(1, $service->getStanineForPercentile(0));
        $this->assertEquals(2, $service->getStanineForPercentile(4));
        $this->assertEquals(2, $service->getStanineForPercentile(4.1));

        $this->assertEquals(2, $service->getStanineForPercentile(10));
        $this->assertEquals(3, $service->getStanineForPercentile(11));
        $this->assertEquals(3, $service->getStanineForPercentile(12));

        $this->assertEquals(3, $service->getStanineForPercentile(22));
        $this->assertEquals(4, $service->getStanineForPercentile(23));
        $this->assertEquals(4, $service->getStanineForPercentile(24));

        $this->assertEquals(4, $service->getStanineForPercentile(39));
        $this->assertEquals(5, $service->getStanineForPercentile(40));
        $this->assertEquals(5, $service->getStanineForPercentile(41));

        $this->assertEquals(5, $service->getStanineForPercentile(59));
        $this->assertEquals(6, $service->getStanineForPercentile(60));
        $this->assertEquals(6, $service->getStanineForPercentile(61));

        $this->assertEquals(6, $service->getStanineForPercentile(76));
        $this->assertEquals(7, $service->getStanineForPercentile(77));
        $this->assertEquals(7, $service->getStanineForPercentile(78));

        $this->assertEquals(7, $service->getStanineForPercentile(88));
        $this->assertEquals(8, $service->getStanineForPercentile(89));
        $this->assertEquals(8, $service->getStanineForPercentile(90));

        $this->assertEquals(8, $service->getStanineForPercentile(95));
        $this->assertEquals(9, $service->getStanineForPercentile(96));
        $this->assertEquals(9, $service->getStanineForPercentile(97));
        $this->assertEquals(9, $service->getStanineForPercentile(104));
    }
}