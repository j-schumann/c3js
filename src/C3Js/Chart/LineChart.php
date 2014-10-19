<?php
namespace C3Js\Chart;

/**
 * Represents a line chart ("single line") in a graph container.
 */
class LineChart extends BaseChart {
    
    /**
     * Sets the id of the x-axis related to this chart.
     * Make sure that the container has a matching x-axis configuartion for that id.
     * @link http://c3js.org/samples/simple_xy_multiple.html
     * @param int $id
     * @throws C3Js\Chart\Exception\InvalidArgumentException
     * @return void 
     */
    public function setXAxis($id) {
        if (!is_int($id) || $id < 1) {
            throw new Exceptions\InvalidArgumentException('Axis id has to be an integer and equal or bigger than 1.');
        }
        $this->config['data']['xs'][$this->getName()] = 'x'.($id);
    }
    
     /**
     * Specifies wether you want this chart to use the primary (1)
     * or the secondary (2) y-axis.
     * 
     * @link http://c3js.org/samples/axes_y2.html
     * @param int $id
     * @throws C3Js\Chart\Exception\InvalidArgumentException
     * @return void 
     */
    public function setYAxis($id) {
        if (!is_int($id) || $id < 1 || $id > 2) {
            throw new Exceptions\InvalidArgumentException('YAxis id has to be 1 or 2.');
        }
        $this->config['data']['axes'][$this->getName()] = 'y'.($id);
        if ($id == 2)
            $this->config['axis']['y2']['show'] = true;
    }
            
}