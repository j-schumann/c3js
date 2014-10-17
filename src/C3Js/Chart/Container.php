<?php
/**
 *  @author      Daniel Klischies <daniel@danielklischies.net>
 */

namespace C3Js\Chart;
use C3Js\Chart\Exception;

/**
 * An instance of this class represents one complete diagram (axis, all charts belonging to it, grids).
 * Basically this is what will be put into c3.generate() to generate the output. You can have multiple
 * containers per page and multiple charts per container.
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
			'axes' => array(),
			'colors' => array(),
			'mimeType' => 'json',
			'types' => array(),
			'xs' => array()
		),
		'axis' => array(
		    'x' => 	array(
                'padding' => array(
                )
            ),
			'x1' => array(
				'padding' => array(
				)
			),
			'x2' => array(
				'padding' => array(
				)
			),
			'y' => array(
				'padding' => array(
				)
			),
			'y2' => array(
				'padding' => array(
				),
				"show" => false
			)
		)
	);

    /**
     * Constructor
     *
     * @param string $dataurl The URL to pull the actual data from
     * @param array $config Optional configuration array, gets merges with defaults
     */
	public function __construct($dataurl, array $config = array()) {
		$this->config['data']['url'] = $dataurl;
		$this->config = array_replace_recursive($this->config, $config);
	}

    /**
     * Converts the container to json so that it can be passed to c3js
     *
     * @return string json
     */
	public function toJson() {
		return htmlspecialchars(json_encode($this->toArray(), JSON_UNESCAPED_UNICODE));
	}

    /**
     * Sets the containers div id. Make sure that there is a container with this id.
     *
     * @param string $id (e.g. #mycontainer)
     * @return void
     */
	public function setId($id) {
		$this->config['bindto'] = $id;
	}

    /**
     * Returns the container id without the leading #.
     *
     * @return string
     */
	public function getIdForHtml() {
		return substr($this->config['bindto'], 1);
	}

    /**
     * Adds a chart to this container.
     *
     * @param \C3Js\Chart\Interfaces\Chart $chart
     * @return void
     */
	public function addChart(Interfaces\Chart $chart) {
		$this->charts[] = $chart;
	}

    /**
     * Sets the padding of an axis.
     *
     * @param string $axis The axis you want to add the padding to (x1, x2, y1...)
     * @param string $where The direction the padding belongs to (top, left, right, bottom)
     * @param float amount (0 means no padding)
     * @link http://c3js.org/samples/axes_y_padding.html
     * @throws \C3Js\Chart\Exception\InvalidArgumentException
     * @return void
     */
	public function setAxisPadding($axis, $where, $amount) {
	    if (!in_array($where, array('top', 'bottom', 'left', 'right')))
            throw new InvalidArgumentException('$where has to be one of "top", "left", "right", "bottom"');
        if (!is_numeric($amount))
            throw new InvalidArgumentException('Padding amount has to be float');
		$this->config['axis'][$axis]['padding'][$where] = $amount;
	}

    /**
     * Sets the label of an axis
     *
     * @param string $axis The axis you want to add the padding to (x1, x2, y1...)
     * @param string $label The label you want to set
     * @link http://c3js.org/samples/axes_label.html
     * @return void
     */
     public function setAxisLabel($axis, $label) {
         $this->config['axis'][$axis]['label']['text'] = $label;
     }

    /**
     * Sets the type of an x-axis.
     *
     * @param int $id The id of the x-axis (equal to the id you used to add an axis to a chart)
     * @param string $type One out of "timeseries", "indexed", "category"
     * @link http://c3js.org/samples/timeseries.html, http://c3js.org/samples/categorized.html
     * @throws \C3Js\Chart\Exception\InvalidArgumentException
     * @return void
     */
	public function setXAxisType($id, $type) {
		if ($type != "timeseries" && $type != "indexed" && $type != "category")
			throw new InvalidArgumentException('Invalid X axis type, has to be one of timeseries, indexed or category.');
		$this->config['axis']['x'.$id]['type'] = $type;
	}

    /**
     *
     */
	public function setXAxisTickFormat($id, $format) {
		if (!isset($this->config['axis']['x'.$id]['tick']))
			$this->config['axis']['x'.$id]['tick'] = array();

		$this->config['axis']['x'.$id]['tick']['format'] = $format;
	}

    /**
     * Returns an array that can be put into c3.generate
     * @param bool $withcharts (true) Specifies wether the charts are written into the array, set it to false if you want to use fromArray later on
     * @return array
     */
	public function toArray($withcharts = true) {
		$config = $this->config;
        if ($withcharts) {
    		foreach ($this->charts as $chart) {
    			$chartconfig = $chart->toArray();
    			$config['data'] = array_replace_recursive($config['data'], $chartconfig['data']);
    			$config['axis']['y2']['show'] = $chartconfig['axis']['y2']['show'] || $config['axis']['y2']['show'];
    		}
        }
		return $config;
	}

    /**
     * Replaces the current configuration array with the one you pass to this function.
     * Make sure that this array is valid, since it isn't structure checked neither merged with a default array.
     * @param array $config
     * @see \C3Js\Chart\Container::toArray()
     */
    public function fromArray($config) {
		$this->config = $config;
	}

}
