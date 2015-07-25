<?php

/**
 * @copyright   (c) 2014, Vrok
 * @license     http://customlicense CustomLicense
 * @author      Daniel Klischies <daniel@danielklischies.net>
 */

namespace C3Js\Chart;

interface ChartInterface
{
    /**
     * Creates a new chart.
     *
     * @param string $name The chart's name, has to be equal to the name in the data json
     */
    public function __construct($name);

    /**
     * Sets the name of the chart.
     *
     * @param string $name The chart's name, has to be equal to the name in the data json
     */
    public function setName($name);

    /**
     * Sets the display name of the chart.
     *
     * @param string $name The chart's display name (e.g. translated name)
     */
    public function setDisplayName($name);

    /**
     * Returns this chart's name.
     *
     * @return string $name
     */
    public function getName();

    /**
     * Sets the chart's color. For areacharts the area color will also be derived from this.
     *
     * @param string $color The (hex-)color you want to set, e.g. #6600ff
     */
    public function setColor($color);

    /**
     * Returns this chart's color.
     *
     * @return string $color
     */
    public function getColor();

    /**
     * Converts this chart to an array for processing by the container.
     *
     * @return array $chart
     */
    public function toArray();
}
