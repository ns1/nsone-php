About
=====

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

### Install (Manual):

### Create config file

### Connect

