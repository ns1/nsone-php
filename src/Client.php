<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */

namespace NSONE;

use NSONE\Config;

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

}

