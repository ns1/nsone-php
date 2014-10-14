<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */


namespace NSONE\Rest;

use NSONE\Rest\BaseResource;

class DataFeed extends BaseResource {

    const ROOT = 'data/feeds';

    public $PASSTHRU_FIELDS = array('name', 'config', 'destinations');
    
    public function list_($sourceid) {
        $url = sprintf('%s/%s', self::ROOT, $sourceid);
        return $this->makeRequest('GET', $url);
    }
    
    public function retrieve($sourceid, $feedid) {
        $url = sprintf('%s/%s/%s', self::ROOT, $sourceid, $feedid);
        return $this->makeRequest('GET', $url);
    }

    public function create($sourceid, $name, $config, $options) {
        $body = array(
            'name': $name,
            'config': $config,
        );
        $this->buildStdBody($body, $options);
        $url = sprintf('%s/%s', self::ROOT, $sourceid);
        return $this->makeRequest('PUT', $url, $body);
    }

    public function update($sourceid, $feedid, $options) {
        $body = array(
            'id': $feedid,
        );
        $this->buildStdBody($body, $options);
        $url = sprintf('%s/%s/%s', self::ROOT, $sourceid, $feedid);
        return $this->makeRequest('POST', $url, $body);
    }

    public function delete($feedid) {
        $url = sprintf('%s/%s', self::ROOT, $feedid);
        return $this->makeRequest('DELETE', $url);
    }


}

