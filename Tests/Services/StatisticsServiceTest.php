<?php


namespace HappyR\NormalDistributionBundle\Tests\Services;

use HappyR\NormalDistributionBundle\Services\StatisticsService;

/**
 * Class StatisticsServiceTest
 *
 * @author Tobias Nyholm
 *
 */
class StatisticsServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetStanine()
    {
        $service=new StatisticsService();

        for ($i=1; $i<=9; $i++) {
            $this->assertEquals($i, $service->getStanine(17, 2, 12+$i));
        }
    }
    
    
    public function testGetStanineForProcentile()
    {
        $service = new StatisticsService();

        $this->assertEquals(1, $service->getStanineForProcentile(-2));
        $this->assertEquals(1, $service->getStanineForProcentile(0));
        $this->assertEquals(2, $service->getStanineForProcentile(4));
        $this->assertEquals(2, $service->getStanineForProcentile(4.1));

        $this->assertEquals(2, $service->getStanineForProcentile(10));
        $this->assertEquals(3, $service->getStanineForProcentile(11));
        $this->assertEquals(3, $service->getStanineForProcentile(12));

        $this->assertEquals(3, $service->getStanineForProcentile(22));
        $this->assertEquals(4, $service->getStanineForProcentile(23));
        $this->assertEquals(4, $service->getStanineForProcentile(24));

        $this->assertEquals(4, $service->getStanineForProcentile(39));
        $this->assertEquals(5, $service->getStanineForProcentile(40));
        $this->assertEquals(5, $service->getStanineForProcentile(41));

        $this->assertEquals(5, $service->getStanineForProcentile(59));
        $this->assertEquals(6, $service->getStanineForProcentile(60));
        $this->assertEquals(6, $service->getStanineForProcentile(61));

        $this->assertEquals(6, $service->getStanineForProcentile(76));
        $this->assertEquals(7, $service->getStanineForProcentile(77));
        $this->assertEquals(7, $service->getStanineForProcentile(78));

        $this->assertEquals(7, $service->getStanineForProcentile(88));
        $this->assertEquals(8, $service->getStanineForProcentile(89));
        $this->assertEquals(8, $service->getStanineForProcentile(90));

        $this->assertEquals(8, $service->getStanineForProcentile(95));
        $this->assertEquals(9, $service->getStanineForProcentile(96));
        $this->assertEquals(9, $service->getStanineForProcentile(97));
        $this->assertEquals(9, $service->getStanineForProcentile(104));
    }


}
 