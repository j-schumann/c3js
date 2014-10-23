<?php
/**
 * @copyright   (c) 2014, Vrok
 * @license     http://customlicense CustomLicense
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
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * Retrieve additional view helpers using factories that are not set in the config.
     *
     * @return array
     */
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'C3Js' => function($helperPluginManager) {
                    $helper = new View\Helper\C3Js();

                    $serviceLocator = $helperPluginManager->getServiceLocator();
                    $config = $serviceLocator->get('Config');
                    if (!empty($config['C3Js']['script_path'])) {
                        $helper->setScriptPath($config['C3Js']['script_path']);
                    }
                    if (!empty($config['C3Js']['settings'])) {
                        $helper->setDefaults($config['C3Js']['settings'], true);
                    }

                    return $helper;
                },
            ),
        );
    }
}
