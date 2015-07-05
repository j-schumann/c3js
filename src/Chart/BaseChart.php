<?php
/**
 * @copyright   (c) 2014, Vrok
 * @license     http://customlicense CustomLicense
 * @author      Daniel Klischies <daniel@danielklischies.net>
 */

namespace C3Js\Chart;

/**
 * Functionality used by all chart types and required for the interface.
 */
abstract class BaseChart implements ChartInterface
{
    /**
     * The identifier used for the legend labels and when retrieving the actual
     * data as JSON from an URL.
     *
     * @var string
     */
    protected $name;

    /**
     * Basic configuration structure for each chart. Will be merged with the
     * container config.
     *
     * @var array
     */
    protected $config = [
        'data' => [
            'axes'   => [],
            'colors' => [],
            'names'  => [],
            'types'  => [],
            'xs'     => []
        ],
        'axis' => [
            'y2' => [
                'show' => false
            ],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct($name)
    {
        $this->name = $name;

        // Extract charttype from classname (strip away namespace and "chart" at
        // the end of the classname, e.g. LineChart -> line

        // @todo Nicht alle charts müssen im gleichen Namespace liegen, daher
        // statt bei statisch bei 11 anzufangen lieber den namespace strippen
        // sauber strippen mit strrpos('\'):
        $this->config['data']['types'][$this->name]
                = strtolower(substr(get_class($this), 11, -5));

        // @todo das prinzip muss ja zukünftig nicht auf areaCharts beschränkt
        // bleiben, evtl kommen später noch andere typen hinzu die im c3 mit
        // Bindestrich notiert werden, im ZF werden auch alle Modul- und Action-
        // Namen die camelcased sind für view-scripte etc in lowercase mit
        // Bindestrich umgewandelt, am besten hier die gleiche Funktionalität
        // verwenden, replace uppercase char mit Bindestrich+lowercase char
        if ($this->config['data']['types'][$this->name] !== "area") {
            str_replace('area', 'area-', $this->config['data']['types'][$this->name]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayName($name)
    {
        $this->config['data']['names'][$this->name] = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setColor($color)
    {
        $this->config['data']['colors'][$this->name] = $color;
    }

    /**
     * {@inheritdoc}
     */
    public function getColor()
    {
        return $this->config['data']['colors'][$this->name];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->config;
    }
}
