<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */

namespace NSONE;

use NSONE\Rest\Records;

class RecordException extends \Exception { }

class Record extends DataObject {

    public $domain = NULL;

    public $type = NULL;

    protected $rest = NULL;

    protected $parentZone = NULL;

    public function __construct($config, $parentZone, $domain, $type) {
        parent::__construct();
        $this->rest = new Records($config);
        $this->parentZone = $parentZone;
        if (strstr($domain, $parentZone->zone) === false)
            $domain .= '.'.$parentZone->zone;
        $this->domain = $domain;
        $this->type = $type;
    }


    public function reload() {
        return $this->load(true);
    }

    public function load($reload=false) {
        if (!$reload && !empty($this->data))
            throw new RecordException('record already loaded');
        $this->data = $this->rest->retrieve($this->parentZone->zone, $this->domain, $this->type);
        return $this;
    }

    public function delete() {
        if (empty($this->data))
            throw new RecordException('record not loaded');
        $this->rest->delete($this->parentZone->zone, $this->domain, $this->type);
        $this->data = array();
    }

    public function update($options) {
        if (empty($this->data))
            throw new RecordException('record not loaded');
        $this->data = $this->rest->update($this->parentZone->zone, $this->domain, $this->type, $options);
        return $this;
    }

    public function create($options) {
        if (!empty($this->data))
            throw new RecordException('record already loaded');
        $this->data = $this->rest->create($this->parentZone->zone, $this->domain, $this->type, $options);
        return $this;
    }


    // qps
    // usage
    // addAnswers

}
