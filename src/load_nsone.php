<?php

/**
 * If you're not using composer, use this to include all the relavent NSONE
 * classes instead of using autoloading
 */
$files = array(
'DataObject.php',
'Client.php',
'Config.php',
'Record.php',
'Rest/BaseResource.php',
'Rest/Transport.php',
'Rest/TransportException.php',
'Rest/CurlTransport.php',
'Rest/DataFeed.php',
'Rest/DataSource.php',
'Rest/Records.php',
'Rest/Stats.php',
'Rest/Zones.php',
'Zone.php',
);
foreach ($files as $f) {
    include($f);
}

