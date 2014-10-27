<?php
/**
 * @copyright   (c) 2014, Vrok
 * @license     http://customlicense CustomLicense
 * @author      Daniel Klischies <daniel@danielklischies.net>
 */

namespace C3Js\Chart;

/**
 * An instance of this class represents one complete diagram (axis, all charts
 * belonging to it, grids).
 * Basically this is what will be put into c3.generate() to generate the output.
 * You can have multiple containers per page and multiple charts per container.
 */
class Container
{
    /**
     * All charts belonging to this container
     *
     * @var array
     */
    protected $charts;

    /**
     * Current configuration (but without charts).
     * If you want to pass this array to C3Js use toArray()
     * in order to add the charts to it.
     *
     * @var array
     */
    private $config = array(
        'data' => array(
            'axes'       => array(),
            'colors'     => array(),
            'mimeType'   => 'json',
            'names'      => array(),
            'types'      => array(),
            'xFormat'    => '%Y-%m-%d %H:%M:%S',
            'xLocaltime' => true,
            'xs'         => array(),
        ),
        'axis' => array(
            'x' => 	array(
                'label' => array(
                    'position' => 'outer-center',
                ),
                'padding' => array(
                    'left' => 0,
                ),
            ),
            'x1' => array(
                'padding' => array(),
            ),
            'x2' => array(
                'padding' => array(),
            ),
            'y' => array(
                'label' => array(
                    'position' => 'outer-center',
                ),
                'padding' => array(
                    'bottom' => 0
                ),
            ),
            'y2' => array(
                'label' => array(
                    'position' => 'outer-center',
                ),
                'padding' => array(),
                'show' => false,
            ),
        ),
    );

    /**
     * Constructor
     *
     * @param string $dataUrl The URL to pull the actual data from
     * @param array $config Optional configuration array, gets merges with defaults
     */
    public function __construct($dataUrl, array $config = array())
    {
        $this->config['data']['url'] = $dataUrl;
        $this->config = array_replace_recursive($this->config, $config);
    }

    /**
     * Converts the container to json so that it can be passed to c3js
     *
     * @return string json
     */
    public function toJson()
    {
        // use Zend\Json\Json with the expression finder instead of json_encode
        // to allow options containing expressions/functions:
        // 'format' => new \Zend\Json\Expr("d3.format('.2f')")
        $json = \Zend\Json\Json::encode(
            $this->toArray(),
            false,
            array('enableJsonExprFinder' => true)
        );

        return htmlspecialchars($json);
    }

    /**
     * Sets the containers div id.
     * Make sure that there is a DOM element/container with this id.
     *
     * @param string $id (e.g. #mycontainer)
     * @return void
     */
    public function setId($id)
    {
        $this->config['bindto'] = $id;
    }

    /**
     * Retrieve the DOM id used for the chart container.
     * Used to retrieve the chart object from the c3helper.
     *
     * @return type
     */
    public function getId()
    {
        return $this->config['bindto'];
    }

    /**
     * Returns the container id without the leading #.
     *
     * @return string
     */
    public function getIdForHtml()
    {
        return substr($this->getId(), 1);
    }

    /**
     * Adds a chart to this container.
     *
     * @param ChartInterface $chart
     * @return void
     */
    public function addChart(ChartInterface $chart)
    {
        $this->charts[] = $chart;
    }

    /**
     * Sets the padding of an axis.
     *
     * @param string $axis The axis you want to add the padding to (x1, x2, y1...)
     * @param string $where The direction the padding belongs to (top, left, right, bottom)
     * @param float amount (0 means no padding)
     * @link http://c3js.org/samples/axes_y_padding.html
     * @throws Exception\InvalidArgumentException
     * @return void
     */
    public function setAxisPadding($axis, $where, $amount)
    {
        if (!in_array($where, array('top', 'bottom', 'left', 'right'))) {
            throw new Exception\InvalidArgumentException(
                    '$where has to be one of "top", "left", "right", "bottom"');
        }
        if (!is_numeric($amount)) {
            throw new Exception\InvalidArgumentException(
                    'Padding amount has to be float');
        }
        $this->config['axis'][$axis]['padding'][$where] = $amount;
    }

    /**
     * Sets the label of an axis
     *
     * @param string $axis The axis you want to set the label for (x, y)
     * @param string $label The label you want to set
     * @link http://c3js.org/samples/axes_label.html
     * @return void
     */
    public function setAxisLabel($axis, $label)
    {
        $this->config['axis'][$axis]['label']['text'] = $label;
     }

    /**
     * Sets the type of an x-axis.
     *
     * @param string $type One out of "timeseries", "indexed", "category"
     * @link http://c3js.org/samples/timeseries.html
     * @link http://c3js.org/samples/categorized.html
     * @throws \C3Js\Chart\Exception\InvalidArgumentException
     * @return void
     */
    public function setXAxisType($type)
    {
        if ($type != "timeseries" && $type != "indexed" && $type != "category") {
            throw new Exception\InvalidArgumentException(
                'Invalid X axis type, has to be one of timeseries, indexed or category.');
        }
        $this->config['axis']['x']['type'] = $type;
    }

    /**
     * Sets an output format for x-axis values. This is mainly intended for use
     * with timeseries x-axis.
     *
     * @param string $format The desired format, e.g. %H:%M
     * @return void
     */
    public function setXAxisTickFormat($format)
    {
        if (!isset($this->config['axis']['x']['tick'])) {
            $this->config['axis']['x']['tick'] = array();
        }
        $this->config['axis']['x']['tick']['format'] = $format;
    }

    /**
     * Sets the format you want to use to parse x-axis values from the ajax
     * response of your controller.
     *
     * @param string $format
     * @link http://c3js.org/samples/axes_x_localtime.html
     * @return void
     */
    public function setXAxisInputFormat($format)
    {
        $this->config['data']['xFormat'] = $format;
    }

    /**
     * Sets the number of ticks on the x-axis.
     *
     * @param int $count Number of ticks to display
     * @return void
     */
    public function setXAxisTickCount($count)
    {
        if (!isset($this->config['axis']['x']['tick'])) {
            $this->config['axis']['x']['tick'] = array();
        }
        $this->config['axis']['x']['tick']['count'] = $count;
    }

    /**
     * Returns an array that can be put into c3.generate.
     *
     * @param bool $withcharts (true) Specifies wether the charts are written
     *     into the array, set it to false if you want to use fromArray later on
     * @return array
     */
    public function toArray($withcharts = true)
    {
        $config = $this->config;
        if ($withcharts) {
            if (empty($this->charts)) {
                throw new Exception\UnderflowException(
                    'You cannot convert an empty container to array, add charts or set $withcharts to false when calling toArray.');
            }

            foreach ($this->charts as $chart) {
                $chartconfig = $chart->toArray();
                $config['data'] = array_replace_recursive($config['data'], $chartconfig['data']);
                $config['axis']['y2']['show'] = $chartconfig['axis']['y2']['show'] || $config['axis']['y2']['show'];
            }
        }

        return $config;
    }

    /**
     * Replaces the current configuration array with the one you pass to this
     * function. Make sure that this array is valid, since it isn't
     * structure-checked neither merged with a default array.
     *
     * @param array $config
     * @see \C3Js\Chart\Container::toArray()
     */
    public function fromArray(array $config)
    {
        $this->config = $config;
    }
}
