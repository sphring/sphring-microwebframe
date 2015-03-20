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


use League\Plates\Engine;
use League\Route\RouteCollection;
use Sphring\MicroWebFramework\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MicroWebFramework
{
    private static $validMethod = ['GET', 'PUT', 'DELETE', 'POST'];
    private $routes;
    /**
     * @var RouteCollection
     */
    private $router;
    /**
     * @var Engine
     */
    private $templateEngine;

    private $helpers = [];

    /**
     * @var array
     */
    private $plateExtensions = [];

    public function __construct()
    {
    }

    /**
     * @MethodInit()
     */
    public function init()
    {
        foreach ($this->plateExtensions as $extension) {
            $this->templateEngine->loadExtension($extension);
        }
        $routes = $this->routes;
        foreach ($routes as $route) {
            $route['controller']->setHelpers($this->helpers);
            $this->router->addRoute($route['method'], $route['route'], function (Request $req, Response $resp, $args) use ($route) {
                $route['controller']->setArgs($args);
                $route['controller']->setRequest($req);
                $resp->setContent($route['controller']->action());
                return $resp;
            });
        }
    }


    public function getRoute($name)
    {
        return $this->routes[$name]["route"];
    }

    /**
     * @return mixed
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param AbstractController[] $routes
     * @Required
     */
    public function setRoutes(array $routes)
    {
        foreach ($routes as $routeName => $route) {
            $this->addRoute($routeName, $route);
        }
    }

    public function addRoute($routeName, $route)
    {
        if (empty($routeName)) {
            throw new \Exception("A route name is missing.");
        }
        if (empty($route['method']) || in_array($route['method'], self::$validMethod)) {
            $route['method'] = 'GET';
        }
        if (empty($route['route'])) {
            throw new \Exception(sprintf("Error for route '%s': route is missing.", $routeName));
        }
        if (empty($route['controller']) || !($route['controller'] instanceof AbstractController)) {
            throw new \Exception(sprintf("Error for route '%s': controller must extend '%s'.", $routeName, AbstractController::class));
        }
        $this->routes[$routeName] = $route;
    }

    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) !== 'get') {
            throw new \Exception(sprintf("Method '%s' doesn't exist.", $name));
        }

    }

    public function deleteRoute($routeName)
    {
        unset($this->routes[$routeName]);
    }


    /**
     * @return Engine
     */
    public function getTemplateEngine()
    {
        return $this->templateEngine;
    }

    /**
     * @param Engine $templateEngine
     * @Required
     */
    public function setTemplateEngine(Engine $templateEngine)
    {
        $this->templateEngine = $templateEngine;
        $this->templateEngine->setFileExtension(null);
    }

    /**
     * @return RouteCollection
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param RouteCollection $router
     * @Required
     */
    public function setRouter(RouteCollection $router)
    {
        $this->router = $router;
    }

    /**
     * @return \ArrayObject
     */
    public function getHelpers()
    {
        return $this->helpers;
    }

    /**
     * @param \ArrayObject $helpers
     * @Required
     */
    public function setHelpers(\ArrayObject $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * @return array
     */
    public function getPlateExtensions()
    {
        return $this->plateExtensions;
    }

    /**
     * @param array $plateExtensions
     * @Required
     */
    public function setPlateExtensions($plateExtensions)
    {
        $this->plateExtensions = $plateExtensions;
    }

}