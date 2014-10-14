<?php

require __DIR__ . '/../vendor/autoload.php';

use NSONE\Client;
use NSONE\Config;

//$config = new Config();
//$config->loadFromFile();
//$config->dump();

$nsone = new Client();
//$nsone->config['verbosity'] = 5;
//$nsone->config->dump();

$zones = $nsone->zones();

print_r($zones->retrieve('test.com'));






