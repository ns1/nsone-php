<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */


namespace NSONE\Rest;

use NSONE\Rest\BaseResource;

class Stats extends BaseResource {

    const ROOT = 'stats';

    public function qps($zone=NULL, $domain=NULL, $type=NULL) {

        $url = '';
        if (empty($zone)) {
            $url = sprintf('%s/%s', self::ROOT, 'qps');
        }
        elseif ($zone && $domain && $type) {
            $url = sprintf('%s/%s/%s/%s/%s', self::ROOT, 'qps', $zone, $domain, $type);
        }
        elseif (!empty($zone)) {
            $url = sprintf('%s/%s/%s', self::ROOT, 'qps', $zone);
        }
        return $this->makeRequest('GET', $url);
    
    }
    
    public function usage($zone=NULL, $domain=NULL, $type=NULL, $options=array()) {

        $url = '';
        if (empty($zone)) {
            $url = sprintf('%s/%s', self::ROOT, 'usage');
        }
        elseif ($zone && $domain && $type) {
            $url = sprintf('%s/%s/%s/%s/%s', self::ROOT, 'usage', $zone, $domain, $type);
        }
        elseif (!empty($zone)) {
            $url = sprintf('%s/%s/%s', self::ROOT, 'usage', $zone);
        }
        $args = array();
        if (isset($options['period']))
            $args['period'] = $options['period'];
        foreach (array('expand', 'aggregate', 'by_tier') as $k) {
            if (isset($options[$k]))
                $args[$k] = (bool)$options[$k];
        }
        return $this->makeRequest('GET', $this->addArgs($url, $args));
    
    }



}

