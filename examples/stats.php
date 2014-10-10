<?php

require __DIR__ . '/../vendor/autoload.php';

use NSONE\Client;
use NSONE\Config;

//$config = new Config();
//$config->loadFromFile();
//$config->dump();

$nsone = new Client();
$nsone->config['verbosity'] = 5;
$nsone->config->dump();
$stats = $nsone->stats();
$out = $stats->makeRequest('GET', 'stats/qps');
var_dump($out);


