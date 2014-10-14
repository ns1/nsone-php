<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */


namespace NSONE\Rest;

use NSONE\Rest\CurlTransport;

class BaseResource {

    protected $config = NULL;

    static protected $transport = NULL;

    public $INT_FIELDS = array();
    public $BOOL_FIELDS = array();
    public $PASSTHRU_FIELDS = array();

    public function __construct(\NSONE\Config $config) {
        $this->config = $config;
        if (self::$transport == NULL)
            self::$transport = new CurlTransport($this->config);
    }

    public function buildStdBody(&$body, $fields) {
        foreach ($this->BOOL_FIELDS as $f) {
            if (isset($fields[$f]))
                $body[$f] = (bool)$fields[$f];
        }
        foreach ($this->INT_FIELDS as $f) {
            if (isset($fields[$f]))
                $body[$f] = (int)$fields[$f];
        }
        foreach ($this->PASSTHRU_FIELDS as $f) {
            if (isset($fields[$f]))
                $body[$f] = $fields[$f];
        }
    }

    protected function addArgs($url, $args) {
        if (!is_array($args))
            return $url;
        $sep = NULL;
        foreach ($args as $k => $v) {
            if (!$sep) {
                $url .= '?'.$k.'='.urlencode($v);
                $sep = '&';
            }
            else {
                $url .= $sep.$k.'='.urlencode($v);
            }
        }
        return $url;
    }

    protected function makeUrl($path) {
        return $this->config->getEndpoint() . $path;
    }

    public function makeRequest($type, $path, $body=NULL, $options=array()) {
        $VERBS = array('GET', 'POST', 'DELETE', 'PUT');
        if (!in_array($type, $VERBS))
            throw new \Exception('invalid request method');
        $options['headers'] = array(
            'User-Agent: nsone-php '.\NSONE\Client::VERSION.' php '.phpversion(),
            'X-NSONE-Key: '.$this->config->getAPIKey()
        );
        $kConfig = $this->config->getKeyConfig();
        if ($kConfig['ignore-ssl-errors'])
            $options['ignore-ssl-errors'] = true;
        return self::$transport->send($type, $this->makeUrl($path), $body, $options);
    }

}
