<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */

namespace NSONE\Rest;

abstract class Transport {

    protected $config = array();

    // HTTP result code we know about, if any
    protected $resultCode = 0;

    public function __construct($config) {
        $this->config = $config;
    }

    /**
     * get current HTTP result code
     * @return int
     */
    public function getResultCode() {
        return $this->resultCode;
    }

    abstract public function send($verb, $url, $body, $options);

}

