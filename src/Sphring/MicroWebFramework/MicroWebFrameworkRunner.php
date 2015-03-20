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


use Arthurh\Sphring\Logger\LoggerSphring;
use Arthurh\Sphring\Runner\SphringRunner;
use League\Route\Http\Exception\NotFoundException;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class MicroWebFrameworkRunner extends SphringRunner
{
    /**
     * @BeforeStart
     */
    public function beforeStart()
    {
        if (!defined("DEVMODE") || !DEVMODE) {
            ini_set("display_errors", false);
            return;
        }
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
    }

    /**
     * @AfterLoad
     */
    public function run()
    {
        $microWebFrameWork = $this->getBean('microwebframe.main');
        $dispatcher = $microWebFrameWork->getRouter()->getDispatcher();
        $request = Request::createFromGlobals();
        try {
            $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
        } catch (NotFoundException $e) {
            $notFoundRoute = $microWebFrameWork->getRoute('notfound');
            $response = $dispatcher->dispatch($notFoundRoute['method'], $notFoundRoute['route']);
        }

        $response->send();
    }
}