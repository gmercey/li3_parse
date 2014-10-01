<?php
/**
 * Cinemur parse
 * Copyright (c) 2014 Cinémur S.A. All rights reserved.
 */

namespace app\extensions\adapter\data\source\http;

use lithium\util\String;
use lithium\util\Inflector;

class Parse extends \lithium\data\source\Http{

    /**
     * Class dependencies.
     */
    protected $_classes = array(
        'service' => 'lithium\net\http\Service',
        'entity' => 'lithium\data\entity\Document',
        'set' => 'lithium\data\collection\DocumentSet',
        'schema' => 'lithium\data\DocumentSchema'
    );

    public function __construct(array $config = array())
    {
        $defaults = array(
            'scheme' => 'https',
            'host' => 'api.parse.com',
            'port' => 443,
            'app_key' => null,
            'rest_key' => null,
            'master_key' => null,
            'auth' => null,
            'version' => 1,
            'methods' => array(
                'create' => array('method' => 'post', 'path' => "{:basePath}/classes/{:source}"),
                'read' => array('method' => 'get', 'path' => "{:basePath}/classes/{:source}/{:id}"),
                'update' => array('method' => 'put', 'path' => "{:basePath}/classes/{:source}/{:id}"),
                'delete' => array('method' => 'delete', 'path' => "{:basePath}/classes/{:source}/{:id}")
            )
        );
        $config += $defaults;
        $config['basePath'] = String::insert('/{:version}', array('version' => $config['version']));

        $config['headers'] = array(
            'X-Parse-Application-Id' => $config['app_key'],
            'X-Parse-REST-API-Key' => $config['rest_key'],
            'Content-Type' => 'application/json'
        );

        parent::__construct($config + $defaults);
    }

    protected function _init()
    {
        parent::_init();

    }

    public function configureClass($class)
    {
        return array(
            'meta' => array('key' => 'objectId', 'locked' => false)
        );
    }

    /**
     * Magic for passing methods to http service.
     *
     * @param string $method
     * @param array $params
     * @return void
     */
    public function __call($method, $params = array())
    {

        list($path, $data, $options) = ($params + array('/', array(), array()));
        return json_decode($this->connection->{$method}($path, $data, $options));
    }

    public function read($query, array $options = array())
    {
        $defaults = array('return' => 'resource', 'model' => $query->model());
        $options += $defaults;
        $params = compact('query', 'options');
        $conn =& $this->connection;
        $config = $this->_config;

        extract($query->export($this));
        switch ($source) {
            case 'users':
                    $query->path(String::insert('{:basePath}/users/{:id}', array('basePath' => $config['basePath'], 'id' => $conditions['id'])));
                break;
        }


        return $this->_filter(__METHOD__, $params, function ($self, $params) use (&$conn, $config) {

            $query = $params['query'];
            $options = $params['options'];
            $params = $query->export($self);
            extract($params, EXTR_OVERWRITE);
            //list($_path, $conditions) = (array) $conditions;

            //$args = (array)$conditions + (array)$limit + (array)$order;

            $result = $conn->get($query->path(), array(), $config['headers']);
            $result = is_string($result) ? json_decode($result, true) : $result;
            $data = $stats = array();

            $result += array('total_rows' => null, 'offset' => null);
            $opts = compact('result') + array('class' => 'set', 'exists' => true);
            return $self->item($query->model(), $data, $opts);
        });
    }
} 