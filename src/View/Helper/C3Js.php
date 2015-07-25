<?php

/**
 * @copyright   (c) 2014, Vrok
 * @license     http://customlicense CustomLicense
 * @author      Daniel Klischies <daniel@danielklischies.net>
 */

namespace C3Js\View\Helper;

use C3Js\Chart\Container;
use Zend\View\Helper\AbstractHelper;

/**
 * Injects the javascripts and css files required for c3js into the header
 * and renders the DOM element with the chart configuration.
 */
class C3Js extends AbstractHelper
{
    /**
     * Flag showing whether the required JS/CSS files were included.
     *
     * @var bool
     */
    protected static $initialized = false;

    /**
     * Stores the last created autoincrement ID for the chart container DOM id.
     *
     * @var int
     */
    protected static $id = 0;

    /**
     * Holds the complete C3JS options.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Prepended to the head-scripts/styles.
     *
     * @var string
     */
    protected $scriptPath = '/c3js';

    /**
     * Either renders the given container or returns the helper instance.
     *
     * @param \C3Js\Chart\Container $container
     */
    public function __invoke(Container $container = null)
    {
        return $container
            ? $this->render($container)
            : $this;
    }

    /**
     * Returns the c3js container and adds the necessary init code to the headscript.
     *
     * @param \C3Js\Chart\Container $container
     *
     * @return string
     */
    public function render(Container $container)
    {
        if (!self::$initialized) {
            $this->includeC3Js();
        }

        // @todo nur automatisch generierte ID verwenden wenn keine individuelle
        // gesetzt wurde
        $container->setId('#chart-'.(self::$id++));

        return '<div id="'.($container->getIdForHtml()).'" data-c3js="'.($container->toJson()).'" class="chart chart--autoload"></div>';
    }

    /**
     * Adds the Javascript and CSS files to the <head>.
     * Call this once from the layout or from every page that displays a c3 Chart.
     * Automatically called by render().
     *
     * @return self
     */
    public function includeC3Js()
    {
        if (self::$initialized) {
            return $this;
        }

        $this->getView()->headLink()
            ->appendStylesheet('//cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.css');
            //->appendStylesheet('/css/c3.min.css');
        $this->getView()->headScript()
            ->appendFile('//cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js')
            ->appendFile('//cdnjs.cloudflare.com/ajax/libs/c3/0.4.10/c3.min.js')
            //->appendFile('/js/c3.js')
            ->appendFile($this->scriptPath.'/c3helper.js');

        self::$initialized = true;

        return $this;
    }

    /**
     * Returns the base path where the c3helper script files are located.
     *
     * @return string
     */
    public function getScriptPath()
    {
        return $this->scriptPath;
    }

    /**
     * Sets the base path where the c3helper script files are located.
     *
     * @param string $path
     *
     * @return self
     */
    public function setScriptPath($path)
    {
        $this->scriptPath = $path;

        return $this;
    }
}
