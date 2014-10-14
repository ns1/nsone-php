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

$stats = $nsone->stats();
print_r($stats->qps());
print_r($stats->qps('test.com'));
print_r($stats->qps('test.com', 'asdf.test.com', 'A'));

print_r($stats->usage());
print_r($stats->usage('test.com'));
print_r($stats->usage('test.com', 'asdf.test.com', 'A'));

print_r($stats->usage('test.com', 'asdf.test.com', 'A', array('period' => '30d')));





