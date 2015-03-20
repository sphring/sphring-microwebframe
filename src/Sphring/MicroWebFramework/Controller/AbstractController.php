<?php
/**
 * Copyright (C) 2015 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 19/03/2015
 */


namespace Sphring\MicroWebFramework\Controller;


use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractController
 * @package Sphring\MicroWebFramework\Controller
 * @method \League\Plates\Engine getEngine()
 */
abstract class AbstractController
{
    protected $helpers = [];
    protected $args = [];

    /**
     * @var Request
     */
    protected $request;

    abstract public function action();

    /**
     * @return \ArrayObject
     */
    public function getHelpers()
    {
        return $this->helpers;
    }

    /**
     * @param \ArrayObject $helpers
     */
    public function setHelpers(\ArrayObject $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * @param $helperName
     * @param $helper
     */
    public function addHelper($helperName, $helper)
    {
        $this->helpers[$helperName] = $helper;
    }

    /**
     * @param $helperName
     */
    public function deleteHelper($helperName)
    {
        unset($this->helpers[$helperName]);
    }

    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) !== 'get') {
            throw new \Exception(sprintf("Method '%s' doesn't exist.", $name));
        }
        $helperName = lcfirst(substr($name, 3));
        if (!isset($this->helpers[$helperName])) {
            throw new \Exception(sprintf("Helper '%s' doesn't exist.", $helperName));
        }
        return $this->helpers[$helperName];
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param array $args
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

}