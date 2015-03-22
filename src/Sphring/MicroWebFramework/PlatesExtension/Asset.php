<?php
namespace Sphring\MicroWebFramework\PlatesExtension;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class Asset extends AbstractHttpExtension implements ExtensionInterface
{
    private $dir;

    public function __construct()
    {
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('asset', [$this, 'getAsset']);
    }

    public function getAsset($asset)
    {
        $assetPath = $this->dir . $asset;
        return $this->getHttpName() . $assetPath;
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
