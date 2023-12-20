$(document).ready(function () {
    var check_screen = $('#algos-view');
    if (check_screen.length > 0) {
        var algosView = jQuery.parseJSON($('#algos-view').attr('data-value'));
        var algosChartDataAll = jQuery.parseJSON($('#algos-chart-data-all').attr('data-value'));

        Object.keys(algosView).forEach(function (k, v) {
            var check_graph_container = $('#hero-graph-' + k);
            if (check_graph_container.length > 0) {
                if (algosView[k]['active'] == 1) {
                    Morris.Line({
                        element: 'hero-graph-' + k,
                        data: algosChartDataAll[k],
                        xkey: 'period',
                        xLabels: 'day',
                        ykeys: ['hash'],
                        labels: [algosView[k]['nice_name']],
                        ymin: parseFloat(algosChartDataAll[k][0]['hash']),
                        yLabelFormat: function (y) {
                            return parseFloat(Math.round(y * 100) / 100).toFixed(0) + ' USD';
                        },
                        xLabelFormat: function (x) {
                            return x.getFullYear() + '-' + (x.getMonth() + 1) + '-' + x.getDate();
                        }
                    });
                }
            }
        });
        $('text[y="319.5"]').attr(
            'style',
            '-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-style: normal; font-variant: normal; font-weight: normal; font-stretch: normal; font-size: 22px; line-height: normal; font-family: sans-serif; background-color: #000000;'
        );
    }

    var check_screen_admin = $('#admin-algos-view');
    if (check_screen_admin.length > 0) {
        var algosView = jQuery.parseJSON($('#admin-algos-view').attr('data-value'));
        var algosChartDataAll = jQuery.parseJSON($('#admin-algos-chart-data-all').attr('data-value'));
        var algosUnits = jQuery.parseJSON($('#admin-algos-chart-units').attr('data-value'));

        Object.keys(algosView).forEach(function (k, v) {
            var check_graph_container = $('#hero-graph-' + k);
            if (check_graph_container.length > 0) {
                if (algosView[k]['active'] == 1) {
                    new Morris.Line({
                        element: 'hero-graph-' + k,
                        data: algosChartDataAll[k],
                        xkey: 'period',
                        xLabels: 'day',
                        ykeys: ['hash'],
                        labels: [algosView[k]['nice_name']],
                        ymin: 0,
                        events: ['2014-02-13'],
                        yLabelFormat: function (y) {
                            return parseFloat(Math.round(y * 100) / 100).toFixed(2) + ' ' + algosUnits[k];
                        },
                        xLabelFormat: function (x) {
                            return (x.getMonth() + 1) + '.' + x.getFullYear()
                        }
                    });
                }
            }
        });

        //ajaxify the numbers

    }
});
