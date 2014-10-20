(function($) {
    $.c3Helper = new function() {
        var charts = {};

        this.init = function() {
            $(".chart--autoload").each(function() {
                $.c3Helper.enableChart(this);
                $(this).removeClass("chart--autoload");
            });
        };

        this.enableChart = function(container) {
            var options = $.parseJSON($(container).attr("data-c3js"));
            var chart = c3.generate(options);
            charts[options.bindto] = chart;
        };

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