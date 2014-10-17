<?php
/**
 *  @author      Daniel Klischies <daniel@danielklischies.net>
 */
 
namespace C3Js\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Injects the javascripts and css files required for c3js into the header
 * and renders the DOM element with the calendar configuration.
 */
class C3Js extends AbstractHelper
{
    protected static $initialized = false;
	protected static $id = 0;
	protected $config = array();
	
	/**
     * Prepended to the head-scripts/styles
     *
     * @var string
     */
    protected $scriptPath = '/c3js';


    /**
     * {@inheritdoc}
     */
    public function __invoke(\C3Js\Chart\Container $container)
    {
        return $this->render($container);
    }

    /**
     * Returns the c3js container and adds the necessary init code to the headscript.
     *
     * @param \C3Js\Chart\Container $container
     * @return string
     */
    public function render(\C3Js\Chart\Container $container)
    {
		if(!self::$initialized)
			$this->includeC3Js();
		$container->setId("#chart-".(self::$id++));
        return '<div id="'.($container->getIdForHtml()).'" data-c3js="'.($container->toJson()).'" class="chart chart--autoload"></div>';
    }

    /**
     * Adds the Javascript and CSS files to the <head>.
     * Call this once from the layout or from every page that displays a c3 Chart.
     *
     * @return self
     */
    public function includeC3Js()
    {
        $this->getView()->headLink()
            ->appendStylesheet('//cdnjs.cloudflare.com/ajax/libs/c3/0.3.0/c3.min.css');
        $this->getView()->headScript()
			->appendFile('//cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.min.js')
			// ->appendFile('//cdnjs.cloudflare.com/ajax/libs/c3/0.3.0/c3.min.js')
			->appendFile('//cdnjs.cloudflare.com/ajax/libs/c3/0.3.0/c3.js')
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
     * @return self
     */
    public function setScriptPath($path)
    {
        $this->scriptPath = $path;
        return $this;
    }

}
