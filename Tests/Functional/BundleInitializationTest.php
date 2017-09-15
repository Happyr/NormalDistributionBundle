<?php

declare(strict_types=1);

namespace Happyr\NormalDistributionBundle\Tests\Functional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Happyr\NormalDistributionBundle\HappyrNormalDistributionBundle;
use Happyr\NormalDistributionBundle\Service\DistributionService;
use Happyr\NormalDistributionBundle\Service\NormalDistributionCalculator;
use Happyr\NormalDistributionBundle\Service\StatisticsService;
use Nyholm\BundleTest\BaseBundleTestCase;

class BundleInitializationTest extends BaseBundleTestCase
{
    protected function getBundleClass()
    {
        return HappyrNormalDistributionBundle::class;
    }

    public function testInitBundle()
    {
        $kernel = $this->createKernel();
        $kernel->addBundle(DoctrineBundle::class);
        $kernel->addConfigFile(__DIR__.'/config.yml');


        // Boot the kernel.
        $this->bootKernel();

        // Get the container
        $container = $this->getContainer();

        $classes = [
            DistributionService::class,
            NormalDistributionCalculator::class,
            StatisticsService::class,
        ];

        foreach ($classes as $class) {
            // Test if you services exists
            $this->assertTrue($container->has($class));
            $service = $container->get($class);
            $this->assertInstanceOf($class, $service);
        }
    }
}
