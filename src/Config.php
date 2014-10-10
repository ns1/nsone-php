<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */

namespace NSONE;

class Config implements \ArrayAccess
{

    const ENDPOINT = 'api.nsone.net';

    const PORT = 443;

    const API_VERSION = 'v1';

    const DEFAULT_CONFIG_FILE = '~/.nsone';

    protected $path = NULL;

    protected $keyID = NULL;

    protected $data = array();

    public function __construct($path = NULL) {

        if ($path) {
            $this->loadFromFile($path);
        }

    }

    protected function expandTilde($path) {
        if (function_exists('posix_getuid') && strpos($path, '~') !== false) {
            $info = posix_getpwuid(posix_getuid());
            $path = str_replace('~', $info['dir'], $path);
        }

        return $path;
    }

    public function loadFromFile($path=NULL) {

        if (empty($path))
            $path = self::DEFAULT_CONFIG_FILE;
        $path = $this->expandTilde($path);
        $contents = file_get_contents($path);
        $this->path = $path;
        $this->loadFromString($contents);

    }

    public function loadFromString($sdata) {

        $this->data = json_decode($sdata, true);
        $this->doDefaults();

    }

    protected function doDefaults() {

        if (isset($this->data['default_key']))
            $this->useKeyID($this->data['default_key']);
        if (!isset($this->data['endpoint']))
            $this->data['endpoint'] = self::ENDPOINT;
        if (!isset($this->data['port']))
            $this->data['port'] = self::PORT;
        if (!isset($this->data['api_version']))
            $this->data['api_version'] = self::API_VERSION;
        if (!isset($this->data['cli']))
            $this->data['cli'] = array();
        if (!isset($this->data['verbosity']))
            $this->data['verbosity'] = 0;

    }

    protected function getData($key, $default=NULL) {

        if (isset($this->data[$key]))
            return $this->data[$key];
        else
            return $default;
    
    }

    public function getKeyConfig($keyID=NULL) {

        if (empty($keyID))
            $keyID = $this->keyID;
        if (empty($keyID))
            throw new \Exception('no keyID specified, no default');
        if (!isset($this->data['keys'][$keyID]))
            throw new \Exception('key ID not found in config: ' . $keyID);
        return $this->data['keys'][$keyID];

    }

    public function useKeyID($keyID) {

        if (!isset($this->data['keys'][$keyID]))
            throw new \Exception('keyID does not exist: ' . $keyID);
        $this->keyID = $keyID;
    
    }

    public function getAPIKey($keyID=NULL) {

        $apiKey = $this->getKeyConfig($keyID);
        if (!isset($apiKey['key']))
            throw new \Exception('invalid config: missing api key');
        return $apiKey['key'];
    
    }

    public function getEndpoint() {

        $port = '';
        $endpoint = '';
        $keyConfig = $this->getKeyConfig();
        if (isset($keyConfig['port']))
            $port = ':' . $keyConfig['port'];
        elseif ($this->data['port'] != self::PORT)
            $port = ':' . $this->data['port'];
        if (isset($keyConfig['endpoint']))
            $endpoint = $keyConfig['endpoint'];
        else
            $endpoint = $this->data['endpoint'];
        return sprintf('https://%s%s/%s/', $endpoint, $port, $this->data['api_version']);
    
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


