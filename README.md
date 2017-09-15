# Happyr Normal Distribution Bundle

[![Latest Version](https://img.shields.io/github/release/Happyr/NormalDistributionBundle.svg?style=flat-square)](https://github.com/Happyr/NormalDistributionBundle/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/Happyr/NormalDistributionBundle/master.svg?style=flat-square)](https://travis-ci.org/Happyr/NormalDistributionBundle)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Happyr/NormalDistributionBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/Happyr/NormalDistributionBundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/Happyr/NormalDistributionBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/Happyr/NormalDistributionBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/95c8e1d1-2b53-45db-a49d-ae772c5f270d/mini.png)](https://insight.sensiolabs.com/projects/95c8e1d1-2b53-45db-a49d-ae772c5f270d)
[![Total Downloads](https://img.shields.io/packagist/dt/happyr/normal-distribution-bundle.svg?style=flat-square)](https://packagist.org/packages/happyr/normal-distribution-bundle)

A bundle to calculate distributions and some statistical important values with those distributions.

## The API

This bundle has three services that helps you with your distribution. Each service and function has comments that
describes how to use them and why. This is a brief overview.

### Calculator

Use this service when you want to calculate a normal distribution. This service has two functions `normalDistribution` 
and `standardDistribution`. The later calculates the unit normal distribution where `meanValue = 0` 
and the `standardDistribution = 1`.

The input to both functions is an array with values like array(3,6,2,6,4,2,3,6,8, ... );

### DistributionManager

This service builds a distribution of any type and saves some data in the database. The public functions to
this service are `addDistribution`, `getPercentile` and `createValueFrequencyArray`.

`DistributionManager::addDistribution` takes an identifier name and an array with the values and frequency as arguments. 
The array must be on the form ($value => $frequency). See example below.

```php
<?php

namespace Acme\DemoBundle\Controller;

use Happyr\NormalDistributionBundle\Service\DistributionManager;

class DemoController
{
    public function testController()
    {
        $distributionService = $this->get(DistributionManager::class);

        $foo = array(8,6,2,6,4,2,3,6,4,8,2,7);
        $bar = $distributionService->createValueFrequencyArray($foo);
        /*
            $bar should now look like this:
            $bar = (
                2 => 3,
                3 => 1,
                4 => 2,
                6 => 3,
                7 => 1,
                8 => 2
            )
        */
        $distributionService->addDistribution('test_id', $bar);

        //get the percentile for a value
        $percentile = $distributionService->getPercentile('test_id', 3.5);

        /* ... */
    }
}

```

### StatisticsHelper

The `StatisticsHelper` is used when you want to get values from a pre-calcuated normal distribution. 

- `getPercentile`: Fetch the percentile for a given value
- `getZTransform`: Perform a Z-transformation to get a normalized value given your normal distribution. 
- `getStanine`: Get the stanine value for a given value
- `getStanineForPercentile`:  Get the stanine value for a given percentile. This is useful when your distribution
isn't a standard distribution. 


## Installation

Install it with Composer!

```bash
composer require happyr/normal-distribution-bundle
```

After the dependencies are downloaded, then register the bundle in the AppKernel.

```php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Happyr\NormalDistributionBundle\HappyrNormalDistributionBundle(),
    // ...
);
```

### Update the database

The bundle contains two entities. You should update your database with a migration script or (if you are in a
pure dev environment) run the following command:

```bash
php app:console doctrine:schema:update --force
```
