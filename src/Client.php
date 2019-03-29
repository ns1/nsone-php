<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */

namespace NSONE;

use NSONE\Config;
use NSONE\Zone;
use NSONE\Record;

class Client
{

    const VERSION = "0.1";

    public $config = NULL;

    public function __construct($options=array()) {

        if (isset($options['config'])) {
            $this->config = $options['config'];
        }
        else {
            $this->config = new Config();
            $this->config->loadFromFile();
        }
    
    }

    /**
     * rest wrapper
     */
    public function stats() {
        return new Rest\Stats($this->config);
    }
    public function zones() {
        return new Rest\Zones($this->config);
    }
    public function records() {
        return new Rest\Records($this->config);
    }
    public function datasource() {
        return new Rest\DataSource($this->config);
    }
    public function datafeed() {
        return new Rest\DataFeed($this->config);
    }

    /**
     * high level interface
     */
    public function loadZone($zone) {
        $zoneH = new Zone($this->config, $zone);
        $zoneH->load();
        return $zoneH;
    }

    public function createZone($zone, $options=array()) {
        $zoneH = new Zone($this->config, $zone);
        $zoneH->create($options);
        return $zoneH;
    }

    public function loadRecord($domain, $type, $zone=NULL) {
        if (empty($zone)) {
            $zone = substr($domain, strpos($domain, '.') + 1);
        }
        $zoneH = new Zone($this->config, $zone);
        return $zoneH->loadRecord($domain, $type);
    }

}

