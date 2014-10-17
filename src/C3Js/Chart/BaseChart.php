<?php
namespace C3Js\Chart;

abstract class BaseChart implements Interfaces\Chart
{
	protected $name;
	protected $config = array(
		'data' => array(
			'axes' => array(),
			'colors' => array(),
			'types' => array(),
			'xs' => array()
		),
		'axis' => array(
			'y2' => array(
				"show" => false
			)
		)
	);
    
	public function __construct($name) {
		$this->name = $name;
		// Extract charttype from classname (strip away namespace and "chart" at the end of the classname)
		// Eg.: LineChart -> line
		$this->config['data']['types'][$this->name] = strtolower(substr(get_class($this), 11, -5));
		if ($this->config['data']['types'][$this->name] !== "area")
			str_replace('area', 'area-', $this->config['data']['types'][$this->name]);
	}
	
	public function setName($name) {
		$this->name = $name;
	}	
	
	public function getName() {
		return $this->name;
	}
	
	public function setColor($color) {
		$this->config['data']['colors'][$this->name] = $color;
	}
	
	public function getColor() {
		return $this->config['data']['colors'][$this->name];
	}
	
	public function toArray() {
		return $this->config;
	}
}