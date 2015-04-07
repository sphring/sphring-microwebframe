<?php
/**
 * Copyright (C) 2014 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 07/04/2015
 */

namespace Sphring\MicroWebFramework\DebugBar;


use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

class RouteCollector extends DataCollector implements Renderable
{
    protected $name;

    protected $data;

    /**
     * @param array $data
     * @param string $name
     */
    public function __construct(array $data = array(), $name = 'routes')
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * Sets the data
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function collect()
    {
        $data = array();
        foreach ($this->data as $k => $v) {
            if (!is_string($v)) {
                $v = $this->getDataFormatter()->formatVar($v);
            }
            $data[$k] = $v;
        }
        return $data;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getWidgets()
    {
        $name = $this->getName();
        return array(
            "$name" => array(
                "icon" => "road",
                "widget" => "PhpDebugBar.Widgets.VariableListWidget",
                "map" => "$name",
                "default" => "{}"
            )
        );
    }
}

