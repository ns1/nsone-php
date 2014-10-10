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

    public function __construct(\NSONE\Config $config) {
        $this->config = $config;
        if (self::$transport == NULL)
            self::$transport = new CurlTransport($this->config);
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
