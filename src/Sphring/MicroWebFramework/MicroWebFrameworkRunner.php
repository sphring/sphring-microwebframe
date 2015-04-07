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


namespace Sphring\MicroWebFramework;


use Arthurh\Sphring\Runner\SphringRunner;
use DebugBar\DebugBar;
use League\Route\Http\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MicroWebFrameworkRunner
 * @package Sphring\MicroWebFramework
 * @RootProject(../../../)
 */
class MicroWebFrameworkRunner extends SphringRunner
{
    /**
     * @AfterLoad
     */
    public function run()
    {
        $debugBar = $this->getDebugBar();
        $debugBar['time']->startMeasure('run', 'Running app');
        $microWebFrameWork = $this->getBean('microwebframe.main');
        $dispatcher = $microWebFrameWork->getRouter()->getDispatcher();
        $request = Request::createFromGlobals();
        $debugBar['time']->startMeasure('render', 'Render route "' . $request->getPathInfo() . '"');
        try {
            $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
        } catch (NotFoundException $e) {
            $notFoundRoute = $microWebFrameWork->getRoute('notfound');
            $response = $dispatcher->dispatch($notFoundRoute['method'], $notFoundRoute['route']);
        }
        $response->send();
        $debugBar['time']->stopMeasure('render');
        $debugBar['time']->stopMeasure('run');
    }

    /**
     * @return DebugBar
     */
    public function getDebugBar()
    {
        return $this->getBean('debugbar');
    }
}
