<?php

/**
 * Copyright (c) 2014 NSONE, Inc
 * Licensed under The MIT License (MIT). See LICENSE in project root
 *
 */


namespace NSONE\Rest;

use NSONE\Rest\BaseResource;

class Records extends BaseResource {

    const ROOT = 'zones';
    
    public $INT_FIELDS = array('ttl');
    public $BOOL_FIELDS = array('use_csubnet');
    public $PASSTHRU_FIELDS = array('feed', 'networks', 'meta', 'regions', 'link');

    # answers must be:
    #  1) a single string
    #     we coerce to a single answer with no other fields e.g. meta
    #  2) a list of single strings
    #     we coerce to several answers with no other fields e.g. meta
    #  3) a list of lists
    #     we have as many answers as are in the outer list, and the
    #     answers themselves are used verbatim from the inner list (e.g. may
    #     have MX style array(10, '1.1.1.1')), but no other fields e.g. meta
    #     you must use this form for MX records, and if there is only one
    #     answer it still must be wrapped in an outer list
    #  4) a list of dicts
    #     we assume the full rest model and pass it in unchanged. must use this
    #     form for any advanced record config like meta data or data feeds
    protected function getAnswersForBody($answers) {
        $realAnswers = array();
        # simplest: they specify a single string ip
        if (is_string($answers))
            $answers = array($answers);
        # otherwise, we need a list
        elseif (!is_array($answers)) 
            throw new \Exception('invalid answers format (must be str or array)');
        # at this point we have a list. loop through and build out the answer
        # entries depending on contents
        foreach ($answers as $a) {
            if (is_string($a)) {
                $realAnswers[] = array('answer' => array($a));
            }
            elseif (is_array($a) && !isset($a['answer'])) {
                $realAnswers[] = array('answer' => $a);
            }
            elseif (is_array($a) && isset($a['answer'])) {
                $realAnswers[] = $a;
            }
            else {
                throw new \Exception('invalid answers format: list must contain only str, list, or dict');
            }
        }
        return $realAnswers;
    }

    # filters must be an list of dict which can have two forms:
    # 1) simple: each item in list is a dict with a single key and value. the
    #            key is the name of the filter, the value is a dict of config
    #            values (which may be empty)
    # 2) full: each item in the list is a dict of the full rest model for
    #          filters (documented elsewhere) which is passed through. use this
    #          for enabled/disabled or future fields not supported otherwise
    #
    protected function getFiltersForBody($filters) {
        $realFilters = array();
        if (!is_array($filters))
            throw new \Exception('filter argument must be an array of arrays');
        foreach ($filters as $f_key => $f_val) {
            if (!is_array($f_val))
                throw new \Exception('filter items must be dict');
            if (isset($f_val['filter'])) {
                # full
                $realFilters[] = $f_val;
            }
            else {
                # simple, synthesize
                $realFilters[] = array('filter' => $f_key, 'config' => (object)$f_val);
            }
        }
        return $realFilters;
    }

    protected function buildBody($zone, $domain, $type, $options) {
        $body = array();
        $body['zone'] = $zone;
        $body['domain'] = $domain;
        $body['type'] = strtoupper($type);
        if (isset($options['filters']))
            $body['filters'] = $this->getFiltersForBody($options['filters']);
        if (isset($options['answers'])) {
            $body['answers'] = $this->getAnswersForBody($options['answers']);
        }
        $this->buildStdBody($body, $options);
        if (isset($body['use_csubnet'])) {
            # key mapping
            $body['use_client_subnet'] = $body['use_csubnet'];
            unset($body['use_csubnet']);
        }
        return $body;
    }

    public function create($zone, $domain, $type, $options) {
        $body = $this->buildBody($zone, $domain, $type, $options);
        $url = sprintf('%s/%s/%s/%s', self::ROOT, $zone, $domain, strtoupper($type));
        return $this->makeRequest('PUT', $url, $body);
    }

    public function update($zone, $domain, $type, $options) {
        $body = $this->buildBody($zone, $domain, $type, $options);
        $url = sprintf('%s/%s/%s/%s', self::ROOT, $zone, $domain, strtoupper($type));
        return $this->makeRequest('POST', $url, $body);
    }

    public function delete($zone, $domain, $type) {
        $url = sprintf('%s/%s/%s/%s', self::ROOT, $zone, $domain, strtoupper($type));
        return $this->makeRequest('DELETE', $url);
    }

    public function retrieve($zone, $domain, $type) {
        $url = sprintf('%s/%s/%s/%s', self::ROOT, $zone, $domain, strtoupper($type));
        return $this->makeRequest('GET', $url);
    }


}

