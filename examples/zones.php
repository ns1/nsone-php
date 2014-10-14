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
print_r($zones->list_());
print_r($zones->retrieve('test.com'));

$options = array('nx_ttl' => 100);
print_r($zones->create('newzone.com', $options));
print_r($zones->update('newzone.com', array('nx_ttl' => 200)));
print_r($zones->delete('newzone.com'));



