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

$records = $nsone->records();
print_r($records->retrieve('test.com', 'asdf.test.com', 'A'));

print_r($records->create('test.com', 'newrec.test.com', 'A', 
    array('answers' => '1.2.3.4')));
print_r($records->delete('test.com', 'newrec.test.com', 'A'));

print_r($records->create('test.com', 'newrec.test.com', 'A', 
    array('answers' => array('1.2.3.4','2.3.4.5'))));
print_r($records->delete('test.com', 'newrec.test.com', 'A'));

print_r($records->create('test.com', 'newrec.test.com', 'MX', 
    array('answers' => array(array(10, '1.2.3.4')))));
print_r($records->delete('test.com', 'newrec.test.com', 'MX'));

print_r($records->create('test.com', 'newrec.test.com', 'A', 
    array('answers' => array(array('answer' => ['1.2.3.4'], 'meta' => array('up' => true))))));
print_r($records->delete('test.com', 'newrec.test.com', 'A'));

