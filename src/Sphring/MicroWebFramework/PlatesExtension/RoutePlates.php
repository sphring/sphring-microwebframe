<?php
namespace Sphring\MicroWebFramework\PlatesExtension;

use Arthurh\RestProxifier\Config;
use Arthurh\RestProxifier\Ui\Ui;
use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Sphring\MicroWebFramework\Exception\MicroWebFrameException;
use Sphring\MicroWebFramework\MicroWebFramework;


class RoutePlates extends AbstractHttpExtension implements ExtensionInterface
{

    public $engine;
    public $template;
    private $fileEndpoint;
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
        $route = $this->microWebFramework->getRouteName($name);
        $args = func_get_args();
        if (empty($route)) {
            throw new MicroWebFrameException("The route '%s' doesn't exist.", $name);
        }
        $pattern = '/(\{\w*\}|\{(.+?):((\{[^\}]*\})*|[^\}])*\})/';
        preg_match_all($pattern, $route, $tabNameInfo);
        $nbNeededValue = count($tabNameInfo[0]);

        if ($nbNeededValue > func_num_args() - 1) {
            $nbValue = count($tabNameInfo[0]) - func_num_args() + 1;
            throw new MicroWebFrameException("You need to follow the pattern \"%s\" miss %s values.", $route, $nbValue);
        }
        if ($nbNeededValue <= 0) {
            return $this->getHttpName() . $this->fileEndpoint . $route;
        }
        for ($i = 0; $i < count($tabNameInfo[0]); $i++) {
            $route = preg_replace($pattern, $args[$i + 1], $route, 1);
        }
        return $this->getHttpName() . $this->fileEndpoint . $route;
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

    /**
     * @return mixed
     */
    public function getFileEndpoint()
    {
        return $this->fileEndpoint;
    }

    /**
     * @param mixed $fileEndpoint
     */
    public function setFileEndpoint($fileEndpoint)
    {
        $this->fileEndpoint = $fileEndpoint;
    }

}