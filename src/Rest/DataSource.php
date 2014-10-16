<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */


namespace NSONE\Rest;

use NSONE\Rest\BaseResource;

class DataSource extends BaseResource {

    const ROOT = 'data/sources';

    public $PASSTHRU_FIELDS = array('name', 'config');
    
    public function list_() {
        $url = sprintf('%s', self::ROOT);
        return $this->makeRequest('GET', $url);
    }

    public function create($name, $sourcetype, $options=array()) {
        $body = array(
            'name' => $name,
            'sourcetype' => $sourcetype,
        );
        $this->buildStdBody($body, $options);
        $url = sprintf('%s', self::ROOT);
        return $this->makeRequest('PUT', $url, $body);
    }

    public function update($sourceid, $options=array()) {
        $body = array(
            'id' => $sourceid,
        );
        $this->buildStdBody($body, $options);
        $url = sprintf('%s/%s', self::ROOT, $sourceid);
        return $this->makeRequest('POST', $url, $body);
    }

    public function delete($sourceid) {
        $url = sprintf('%s/%s', self::ROOT, $sourceid);
        return $this->makeRequest('DELETE', $url);
    }

    public function retrieve($sourceid) {
        $url = sprintf('%s/%s', self::ROOT, $sourceid);
        return $this->makeRequest('GET', $url);
    }

    public function publish($sourceid, $data) {
        $url = sprintf('feed/%s', $sourceid);
        return $this->makeRequest('POST', $url, $data);
    }

}

