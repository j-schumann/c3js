(function($) {
    $.c3Helper = new function() {
        /**
         * Registry of the created chart objects index by their DOM id.
         *
         * @type object
         */
        var charts = {};

        /**
         * Selects all marked containers after DOMload and creates the charts.
         *
         * @returns {undefined}
         */
        this.init = function() {
            $(".chart--autoload").each(function() {
                $.c3Helper.enableChart(this);
                $(this).removeClass("chart--autoload");
            });
        };

        /**
         * Generates a chart using the options stored in the data attribute
         * of the container given via its DOM selector.
         *
         * @param {string} container
         * @returns {undefined}
         */
        this.enableChart = function(container) {
            // do not use $.parseJSON to allow expressions / functions like
            // {format: d3.format(".2f")} in the options, these are set using
            // Zend\Json\Expr
            var options = eval('(' + $(container).attr("data-c3js")+')');

            var chart = c3.generate(options);
            charts[options.bindto] = chart;
        };

        /**
         * Retrieve a chart object by its DOM id.
         *
         * @param {string} id
         * @returns {c3}
         */
        this.getChart = function(id) {
            return charts[id];
        };

        /**
         * Retrieve the minimum data value of the first data set.
         * Used to set the minimum value of the y-axis as there is no option
         * to set the range to "auto".
         *
         * @param {string} id
         * @returns {float}
         * @todo funktioniert nur beschränkt bei 2 y-achsen
         */
        this.getMinData = function(id) {
            var c = charts[id];

            // c.data.get(0) is not working...
            var values = c.internal.data.targets[0].values;

            var min = null;
            for (v in values) {
                if (min === null || values[v].value < min) {
                    min = values[v].value;
                }
            }

            return min;
        };

        /**
         * Sets the minimum value for the y-axis to the given value.
         *
         * @param {string} id
         * @param {float} min
         * @todo funktioniert nur beschränkt bei 2 y-achsen
         */
        this.setYAxisMin = function(id, min) {
            if (min === null) {
                min = this.getMinData(id);
            }

            charts[id].axis.min(min);
        };

        /**
         * Toggle the range of the y-axis between 0 and the data minimum.
         *
         * @param {string} id
         * @todo funktioniert nur beschränkt bei 2 y-achsen
         */
        this.toggleYAxisMin = function(id) {
            var c = charts[id];
            var min = this.getMinData(id);

            if (typeof(c.internal.config.axis_y_min) === 'undefined'
                || c.internal.config.axis_y_min == min
            ) {
                c.axis.min(0);
            } else {
                c.axis.min(min);
            }
        };
    };

    $(document).ready(function() {
        $.c3Helper.init();
    });

})(jQuery);