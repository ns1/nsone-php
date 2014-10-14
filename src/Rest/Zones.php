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
    
    protected $INT_FIELDS = array('retry', 'refresh', 'expiry', 'nx_ttl');
    protected $PASSTHRU_FIELDS = array('secondary', 'hostmaster', 'meta', 'networks');

    protected function buildBody($zone, $options) {
        $body = array();
        $body['zone'] = $zone;
        $this->buildStdBody($body, $options);
        return $body;
    }

    public function retrieve($zone=NULL) {

        $url = sprintf('%s/%s', self::ROOT, $zone);
        return $this->makeRequest('GET', $url);
    
    }
    


}

