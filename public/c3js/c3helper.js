(function($) {
    $.c3Helper = new function() {
        var charts = {};

        this.init = function() {
            $(".chart--autoload").each(function() {
                $.c3Helper.enableChart(this);
                $(this).removeClass("chart--autoload");
            });
        }
        
        this.enableChart = function(container) {
            var options = $.parseJSON($(container).attr("data-c3js"));
            var chart = c3.generate(options);
            charts[options.bindto] = chart;
        }
        
        this.getChart = function(id) {
            return charts[id];
        }
        
    }
    
    $(document).ready(function() {
        $.c3Helper.init();
    });	

})(jQuery);