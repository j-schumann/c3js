<?php

/**
 * @copyright   (c) 2014-16, Vrok
 * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author      Daniel Klischies <daniel@danielklischies.net>
 */

namespace C3Js;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

/**
 * Module bootstrapping.
 */
class Module implements ConfigProviderInterface, ViewHelperProviderInterface
{
    /**
     * Returns the modules default configuration.
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__.'/../config/module.config.php';
    }

    /**
     * Retrieve additional view helpers using factories that are not set in the config.
     *
     * @return array
     */
    public function getViewHelperConfig()
    {
        return [
            'factories' => [
                'c3Js' => function ($sl) {
                    $helper = new View\Helper\C3Js();

                    $config = $sl->get('Config');
                    if (!empty($config['C3Js']['script_path'])) {
                        $helper->setScriptPath($config['C3Js']['script_path']);
                    }
                    if (!empty($config['C3Js']['settings'])) {
                        $helper->setDefaults($config['C3Js']['settings'], true);
                    }

                    return $helper;
                },
            ],
        ];
    }
}
