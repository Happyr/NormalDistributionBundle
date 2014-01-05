# HappyR Normal Distribution Bundle

A bundle to calculate normal distribution and other related stuff

Installation
------------

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

