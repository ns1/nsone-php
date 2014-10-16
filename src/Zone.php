<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */

namespace NSONE;

use NSONE\DataObject;
use NSONE\Record;
use NSONE\Rest\Zones;
use NSONE\Rest\Stats;

class ZoneException extends \Exception { }

class Zone extends DataObject {

    protected $config = NULL;

    public $zone = NULL;

    protected $rest = NULL;

    public function __construct($config, $zone) {
        parent::__construct();
        $this->config = $config;
        $this->zone = $zone;
        $this->rest = new Zones($this->config);
    }

    public function __call($name, $args) {
        if (substr($name, 0, 4) != 'add_')
            return NULL;
        $rtype = strtoupper(substr($name, strpos($name, '_') + 1));
        $domain = $args[0];
        $options['answers'] = $args[1];
        if (sizeof($args) == 3) {
            $options = array_merge($options, $args[2]);
        }
        $rec = new Record($this->config, $this, $domain, $rtype);
        $rec->create($options);
        return $rec;
    }

    public function load($reload = false) {
        if (!$reload && !empty($this->data))
            throw new ZoneException('zone already loaded');

        $this->data = $this->rest->retrieve($this->zone);
        return $this;
    }

    public function reload() {
        return $this->load(true);
    }


    public function delete() {
        $this->rest->delete($this->zone);
        $this->data = array();
    }

    public function update($options) {
        if (empty($this->data))
            throw new ZoneException('zone not loaded');
        $this->data = $this->rest->update($this->zone, $options);
        return $this;
    }

    public function create($options) {
        if (!empty($this->data))
            throw new ZoneException('zone already loaded');
        $this->data = $this->rest->create($this->zone, $options);
        return $this;
    }

    public function loadRecord($domain, $type) {
        $rec = new Record($this->config, $this, $domain, $type);
        $rec->load();
        return $rec;
    }

    public function qps() {
        $stats = new Stats($this->config);
        return $stats->qps($this->zone);
    }

    public function usage() {
        $stats = new Stats($this->config);
        return $stats->usage($this->zone);
    }

    // linkRecord(existing_domain, new_domain, rtype)
    // cloneRecord(existing_domain, new_domain, rtype, zone)

}

