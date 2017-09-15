<?php

declare(strict_types=1);

namespace Happyr\NormalDistributionBundle\Tests\Functional;

use Happyr\NormalDistributionBundle\HappyrNormalDistributionBundle;
use Nyholm\BundleTest\BaseBundleTestCase;

class BundleInitializationTest extends BaseBundleTestCase
{
    protected function getBundleClass()
    {
        return HappyrNormalDistributionBundle::class;
    }

    public function testInitBundle()
    {
        // Boot the kernel.
        $this->bootKernel();

        // Get the container
        $container = $this->getContainer();

        // Test if you services exists
        $this->assertTrue($container->has('acme.foo'));
        $service = $container->get('acme.foo');
        $this->assertInstanceOf(Foo::class, $service);
    }
}
