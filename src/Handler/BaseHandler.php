<?php

namespace Handler;

abstract class BaseHandler
{

//    protected static $instance;
//    protected $service;
//
//    protected function __construct($service) {
//        $this->service = $service;
//    }
//
//    public static function getInstance($container) {
//        if (!isset(self::$instance)) {
//            self::$instance = new static(null);
//        }
//        return self::$instance;
//    }

    protected function get($params = null)
    {
        // Need new exception here
    }

    protected function post($params = null)
    {
        // Need new exception here

    }

    protected function put($params = null)
    {
        // Need new exception here

    }

    protected function delete($params = null)
    {
        // Need new exception here
    }

    public function handle($method, $urlParams){
        $method = strtolower($method);
        echo $this->$method($urlParams);
    }

}