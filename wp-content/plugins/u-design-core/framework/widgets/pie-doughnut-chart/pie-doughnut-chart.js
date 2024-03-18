/**
 * Alpha Pie-Doughnut Chart Library
 * 
 * @package Alpha Core FrameWork
 * @since 1.0.0
 */

'use strict';

window.theme = window.theme || {};

( function ( $ ) {
    /**
     * Initialize pieDoughbut Chart
     * 
     * @since 1.0.0
     * @param {string} selector
     */
    theme.initPieDoughnutChart = function ( selector ) {
        if ( 'undefined' == typeof ( Chart ) ) {
            return;
        }
        theme.$( selector ).each( function () {
            var $chart = $( this ),
                $chart_canvas = $chart.find( '.pie-doughnut-chart' ),
                data = $chart.data( 'chart' ) || {},
                type = $chart.data( 'type' ),
                options = $chart.data( 'options' ) || {};

            if ( true === options.show_tooltip ) {
                options.tooltips.callbacks = {
                    label: function ( tooltipItem, data ) {
                        return ' ' + data.labels[ tooltipItem.index ] + ': ' + data.datasets[ tooltipItem.datasetIndex ].data[ tooltipItem.index ];
                    }
                }
            }

            if ( !$chart.length ) {
                return;
            }

            var intObsOptions = {
                rootMargin: '200px 0px 200px 0px'
            };

            theme.appear( $chart_canvas[ 0 ], function () {
                // If chart is created, return
                if ( $chart.find( '.chartjs-size-monitor' ).length ) {
                    return;
                }
                var chartInstance = new Chart( $chart_canvas, {
                    type: type,
                    data: data,
                    options: options
                } );
            }, intObsOptions );
        } );
    }

    $( window ).on( 'alpha_complete', function () {
        theme.initPieDoughnutChart( '.pie-doughnut-chart-container' );
    } )
} )( jQuery );