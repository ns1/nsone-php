<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */

namespace NSONE;

class DataObject implements \ArrayAccess
{

    protected $data = array();

    public function __construct() {
    }

    public function getData($key=NULL, $default=NULL) {
        if (empty($key))
            return $ths->data;
        if (isset($this->data[$key]))
            return $this->data[$key];
        else
            return $default;
    }

    public function dump($return=false) {
        print_r($this->data, $return);
    }
    
    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }
    
    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        return $this->data[$offset];
    }
}


