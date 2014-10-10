<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */

namespace NSONE\Rest;

use NSONE\Rest\Transport;
use NSONE\Rest\TransportException;

/**
 * an implementation of a transport using CURL
 */
class CurlTransport extends Transport {

    /**
     * read buffer
     * @var string
     */
    protected $readBuf;

    /**
     * receive callback function used by curl
     * @param resource $cH curl handle
     * @param string $data data received
     * @return int length of data received
     */
    protected function recv($cH, $data) {
        $this->readBuf .= $data;
        return strlen($data);
    }

    public function send($verb, $url, $body, $options) {

        $this->readBuf = '';
        $curl = curl_init($url);
        if (empty($curl)) {
            throw new TransportException("unable to initialize cURL");
        }

        // XXX leaks curl handle on exception?

        curl_setopt($curl, CURLOPT_WRITEFUNCTION, array($this, 'recv'));

        if (isset($options['timeout']))
            curl_setopt($curl, CURLOPT_TIMEOUT, $options['timeout']);

        if (@$options['ignore-ssl-errors']) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (!empty($options['headers'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);
        }

        if (@$this->config['verbosity'] > 2)
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);

        switch ($verb) {
            case 'GET':
            case 'PUT':
            case 'POST':
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $verb);
                break;
            default:
                throw new TransportException("unhandled cURL verb: {$verb}");
        }

        curl_exec($curl);
        $out = $this->readBuf;

        $this->resultCode = $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (@$this->config['verbosity'] > 2) {
            $data = curl_getinfo($curl, CURLINFO_HEADER_OUT);
            $outHeaders = print_r($options['headers'], true);
            echo "---------------------------send-------------------------\n";
            //echo "OUT HEADERS: [$outHeaders]\n";
            echo "WRITE: [$data]\n";
            echo "READ : [$this->readBuf]\n";
            echo "-------------------------end send-----------------------\n";
        }

        $error = curl_error($curl);
        curl_close($curl);

        if (empty($out)) {
            throw new TransportException("unable to connect, no response, or timeout: ".
                                         $fullURL." CURL Error: " . $error, $code);
        }

        $jsonOut = json_decode(trim($out), true);
        if (empty($jsonOut)) {
            $e = new TransportException("invalid JSON response: ".$out, $code);
            $e->rawResult = $out;
            throw $e;
        }

        if ($this->resultCode != 200) {
            if (isset($jsonOut['message']))
                $out = $jsonOut['message'];
            $e = new TransportException("request failed: ".$out, $this->resultCode);
            $e->rawResult = $out;
            throw $e;
        }

        return $jsonOut;

    }

}

?>
