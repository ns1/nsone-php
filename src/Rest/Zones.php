<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */


namespace NSONE\Rest;

use NSONE\Rest\BaseResource;

class Zones extends BaseResource {

    const ROOT = 'zones';
    
    public $INT_FIELDS = array('retry', 'refresh', 'expiry', 'nx_ttl');
    public $PASSTHRU_FIELDS = array('secondary', 'hostmaster', 'meta', 'networks', 'link');

    protected function buildBody($zone, $options) {
        $body = array();
        $body['zone'] = $zone;
        $this->buildStdBody($body, $options);
        return $body;
    }

    public function create($zone, $options) {
        $body = $this->buildBody($zone, $options);
        $url = sprintf('%s/%s', self::ROOT, $zone);
        return $this->makeRequest('PUT', $url, $body);
    }

    public function update($zone, $options) {
        $body = $this->buildBody($zone, $options);
        $url = sprintf('%s/%s', self::ROOT, $zone);
        return $this->makeRequest('POST', $url, $body);
    }

    public function delete($zone) {
        $url = sprintf('%s/%s', self::ROOT, $zone);
        return $this->makeRequest('DELETE', $url);
    }

    public function list_() {
        $url = sprintf('%s', self::ROOT);
        return $this->makeRequest('GET', $url);
    }

    public function retrieve($zone) {
        $url = sprintf('%s/%s', self::ROOT, $zone);
        return $this->makeRequest('GET', $url);
    }


}

