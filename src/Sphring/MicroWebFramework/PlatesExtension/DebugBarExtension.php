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

namespace Sphring\MicroWebFramework\PlatesExtension;


use DebugBar\DebugBar;
use DebugBar\JavascriptRenderer;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class DebugBarExtension extends AbstractHttpExtension implements ExtensionInterface
{
    private $dir;
    /**
     * @var DebugBar
     */
    private $debugBar;
    /**
     * @var JavascriptRenderer
     */
    private $debugBarRenderer;
    private $devMode = true;

    public function register(Engine $engine)
    {
        $engine->registerFunction('debugBarRender', [$this, 'render']);
        $engine->registerFunction('debugBarRenderHead', [$this, 'renderHead']);
        $engine->registerFunction('debugBarRenderer', [$this, 'getDebugBarRenderer']);
    }

    public function getDebugBarRenderer()
    {
        return $this->debugBarRenderer;
    }

    public function renderHead()
    {
        if (!$this->isDevMode()) {
            return "";
        }
        return $this->debugBarRenderer->renderHead();
    }

    public function render()
    {
        if (!$this->isDevMode()) {
            return "";
        }
        return $this->debugBarRenderer->render();
    }

    /**
     * @return DebugBar
     */
    public function getDebugBar()
    {
        return $this->debugBar;
    }

    public function init()
    {
        $this->debugBarRenderer = $this->debugBar->getJavascriptRenderer($this->getHttpName() . $this->dir . 'debugbar');
    }

    /**
     * @param DebugBar $debugBar
     * @Required
     */
    public function setDebugBar($debugBar)
    {
        $this->debugBar = $debugBar;
    }

    /**
     * @return boolean
     */
    public function isDevMode()
    {
        return $this->devMode;
    }

    /**
     * @param boolean $devMode
     */
    public function setDevMode($devMode)
    {
        $this->devMode = $devMode;

    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @param string $dir
     * @Required
     */
    public function setDir($dir)
    {
        if ($dir[strlen($dir) - 1] !== '/') {
            $dir .= '/';
        }
        $this->dir = $dir;
    }
}
