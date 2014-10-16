<?php

require __DIR__ . '/../vendor/autoload.php';

use NSONE\Client;
use NSONE\Config;

// use default ~/.nsone config
$nsone = new Client();
// higher verbosity dumps http traffic to stdout
$nsone->config['verbosity'] = 5;

// the rest wrapper: getting the zone list
$zoneClient = $nsone->zones();
$zoneList = $zoneClient->list_();
print_r($zoneList);

// the higher level classes: creating a zone
$zone = $nsone->createZone('newtestzone.com', array('nx_ttl' => 100));
$zone->dump();
// access $zone object as an array
echo "zone: {$zone['zone']}\n";
// or get all values
print_r($zone->getData());
// some convenience methods
print_r($zone->qps());

// loading a zone
$zone = $nsone->loadZone('newtestzone.com');

// adding simple records
$zone->add_A('newrecord', '1.2.3.4');
$zone->add_CNAME('newcname', 'newrecord.newtestzone.com');

// complex configuration. this will be simplied via
// the high levels objects eventually
$answers[] = array(
    'answer' => array('1.2.3.4'), // even though an array, only one ip here. array is for MX
                                  // where first element is priority, second is IP
    'meta' => array(
        'up' => true,
        'country' => array('US'),
    )
);
$answers[] = array(
    'answer' => array('2.3.4.5'),
    'meta' => array(
        'up' => true,
        'country' => array('FR'),
    )
);
$filters = array(
    'geotarget_country' => array(), // no configuration
    'select_first_n' => array('N' => 1) // configuration value of N = 1
);
// complete list of options in src/Rest/Records.php, the *_FIELDS class properties
$options['filters'] = $filters;
$options['use_csubnet'] = true;
$rec = $zone->add_A('complex', $answers, $options);

// DATA SOURCES/FEEDS

// create an NSONE API data source. note, this only needs to happen once!
$sourceClient = $nsone->datasource();
$s = $sourceClient->create('my api source', 'nsone_v1');
$sourceID = $s['id'];

// create feeds from this source for each answer
$feedClient = $nsone->datafeed();
// feed for server 1
$feedClient->create($sourceID,
               'feed to server1',
               array('label' => 'server1'),
               array('destinations' => array(
                   array('desttype' => 'answer',
                          // use ids from the record object
                          'record' => $rec['id'],
                          'destid' => $rec['answers'][0]['id']
                      ))));
// feed for server 2
$feedClient->create($sourceID,
               'feed to server2',
               array('label' => 'server2'),
               array('destinations' => array(
                   array('desttype' => 'answer',
                          'record' => $rec['id'],
                          'destid' => $rec['answers'][1]['id']
                        ))));


// now publish an update via feed to the records
$sourceClient->publish($sourceID, array(
    'server1' => array(
        'up' => true
    ),
    'server2' => array(
        'up' => false
    )));

// cleanup
// deletes all records, including their meta and feeds
$zone->delete(); 
// delete the data source. not normally necessary.
$sourceClient->delete($sourceID);
