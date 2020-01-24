About
=====
> This project is [inactive](https://github.com/ns1/community/blob/master/project_status/INACTIVE.md).

This package provides a PHP SDK for accessing the NSONE DNS platform and includes both a simple NSONE REST API wrapper as well as a higher level interface for managing zones, records, data feeds, and more. 

It requires PHP 5.3+ and the curl extension.

Getting Started 
===============

### Create an API key

You'll need a REST API key. Login to the your account at http://my.nsone.net (or create a new, free account at http://nsone.net/signup). Click on Account in the top right, then Settings & Users. At the bottom, in the Manage API Keys section, click Add a New Key and set an appropriate name. If you wish, adjust permissions for this key.

### Install nsone-php (Using Composer):

We recommend using composer (http://getcomposer.org) to manage the nsone-php package. If you don't already have it, first install Composer into your project directory:

```bash
curl -sS https://getcomposer.org/installer | php
```

Edit (or create) composer.json in your project root to include nsone-php:

```javascript
{
    "require": {
        "nsone/nsone-php": "~0.1"
    }
}
```

Then in your project, make sure you require the composer autoloader:

```php
require 'vendor/autoload.php';
```

### Create config file

For example, nsone.json

```javascript
{
   "default_key": "account1",
   "verbosity": 5,
   "keys": {
        "account1": {
            "key": "qACMD09OJXBxT7XOuRs8",
            "desc": "account number 1"
        },
        "account2": {
            "key": "qACMD09OJXBxT7XOwv9v",
            "desc": "account number 2"
        }
   }
}
```

### Connect

```php
require 'vendor/autoload.php';

use NSONE\Client;
use NSONE\Config;

$config = new Config();
$config->loadFromFile('nsone.json');

$nsone = new Client(array('config' => $config));

$zone = $nsone->createZone('newzone2.com', array('nx_ttl' => 100));
$zone->update(array('nx_ttl' => 200));
print_r($zone->qps());
print_r($zone->usage());
$zone->delete();

$zone = $nsone->loadZone('test.com');
print_r($zone->qps());
```

Contributions
---
Pull Requests and issues are welcome. See the [NS1 Contribution Guidelines](https://github.com/ns1/community) for more information.
