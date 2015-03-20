<?php
namespace Sphring\MicroWebFramework\PlatesExtension;

use Arthurh\RestProxifier\Config;
use Arthurh\RestProxifier\Ui\Ui;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Sphring\MicroWebFramework\MicroWebFramework;


class RoutePlates implements ExtensionInterface
{

    public $engine;
    public $template;
    private $httpName;
    /**
     * @var MicroWebFramework
     */
    private $microWebFramework;

    public function __construct()
    {

    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('route', [$this, 'getRoute']);
    }

    public function getRoute($name)
    {
        $route = $this->microWebFramework->getRoute($name);
        $route = preg_replace('#\{.*\}#i', '', $route);
        return $this->httpName . $route;
    }

    /**
     * @MethodInit
     */
    public function loadHttpName()
    {
        if (empty($_SERVER["REQUEST_SCHEME"])) {
            if (!empty($_SERVER["HTTPS"])) {
                $_SERVER["REQUEST_SCHEME"] = 'https';
            } else {
                $_SERVER["REQUEST_SCHEME"] = 'http';
            }
        }
        $port = "";
        $servername = dirname($_SERVER['SCRIPT_NAME']);
        if ($servername == '/') {
            $servername = null;
        }
        if (!($_SERVER['SERVER_PORT'] == 80 && $_SERVER["REQUEST_SCHEME"] == 'http') &&
            !($_SERVER['SERVER_PORT'] == 443 && $_SERVER["REQUEST_SCHEME"] == 'https')
        ) {
            $port = ':' . $_SERVER['SERVER_PORT'];
        }
        $fileIndex = '/index.php';
        $this->httpName = $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["SERVER_NAME"] . $port . $servername . $fileIndex;
    }

    /**
     * @return MicroWebFramework
     */
    public function getMicroWebFramework()
    {
        return $this->microWebFramework;
    }

    /**
     * @param MicroWebFramework $microWebFramework
     * @Required
     */
    public function setMicroWebFramework(MicroWebFramework $microWebFramework)
    {
        $this->microWebFramework = $microWebFramework;
    }

}