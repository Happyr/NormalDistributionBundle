# HappyR Normal Distribution Bundle

A bundle to calculate normal distribution and other related stuff

##Installation

### Step 1: Using Composer

Install it with Composer!

```js
// composer.json
{
    // ...
    require: {
        // ...
        "happyr/normal-distribution-bundle": "dev-master",
    }
}
```

Then, you can install the new dependencies by running Composer's ``update``
command from the directory where your ``composer.json`` file is located:

```bash
$ php composer.phar update
```

### Step 2: Register the bundle

 To register the bundles with your kernel:

```php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new HappyR\NormalDistributionBundle\HappyRNormalDistributionBundle(),
    // ...
);
```

### Step 3: Update the database

The bundle contains two entities. You should update your database with a migration script or (if you are in a
pure dev environment) run the following command:

```bash
php app:console doctrine:schema:update --force
```

## The API

This bundle has three services that helps you with your distribution. Each service and function has comments that
describes how to use them and why. This is a brief overview.

### Normal Distribution Calculator

Use this service when you want to calculate a normal distribution. This service has to functions
```calculateNormalDistribution``` and ```calculateStandardNormalDistribution```. The later calculates
the unit normal distribution where mean value=0 and the standard distribution=1.

The input to both functions is an array with values like array(3,6,2,6,4,2,3,6,8, ... );

### Distribution Service

This service calculates a distribution of any type and saves some data in the database. The public functions to
this service are ```addDistribution```, ```getPercentile``` and ```createValueFrequencyArray```.

**Add distribution** takes an identifier name and an array with the values an frequency as arguments. The array must
be on the form ($value => $frequency). See example below.

```php
<?php

namespace Acme\DemoBundle\Controller;

/* ... */

class DemoController
{
    /* ... */

    public function testController()
    {
        $distributionService = $this->get('happyr.normal_distribution.distribution_service');

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
        $percentile = $distributionService->getPercentile('test_id', 3.5)

        /* ... */
    }
}

```

### Statistics Service

The statistics service is made for get values from an existing normal distribution. You may fetch the percentile for a
value with ```getPercentile```. If you what to do a z-transform to get the value in a standard normal distribution you
may use ```getZTransform```. Use ```getStanine``` to get the stanine value for a value in the normal distribution.

The last function of this class returns the stanine value for a given percentile. This is useful when your distribution
isn't a standard distribution. Use DistributionService->getPercentile() and then StatisticsService->getStanineForPercentile()