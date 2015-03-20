<?php
/**
 * Copyright (C) 2015 Arthur Halet
 *
 * This software is distributed under the terms and conditions of the 'MIT'
 * license which can be found in the file 'LICENSE' in this package distribution
 * or at 'http://opensource.org/licenses/MIT'.
 *
 * Author: Arthur Halet
 * Date: 20/03/2015
 */


namespace Sphring\MicroWebFramework;


use Arthurh\Sphring\Sphring;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MicroWebFrameworkTest extends \PHPUnit_Framework_TestCase
{
    public function testMain()
    {
        $sphring = new Sphring(__DIR__ . '/../../../sphring/main.yml');
        $sphring->loadContext();
        $microWebFrameWork = $sphring->getBean('microwebframe.main');
        $dispatcher = $microWebFrameWork->getRouter()->getDispatcher();
        $request = Request::createFromGlobals();
        $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotNull($response->getContent());
    }
}