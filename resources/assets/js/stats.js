$(document).ready(function() {
    // Load status
    if (window.location.pathname == '/stats') {
        loadGraphs();
        loadDeaths();
        loadKills();
    }
});

/**
 * Load table for character deaths
 */
var loadDeaths = function() {
    $.ajax({
        url : '/stats/deaths',
        method : 'GET',
        success : function(resp) {
            $('#most-deaths').empty();
            $('#most-deaths').append(resp);
        },
        error : function(err) {
            console.log(err);
        }
    });
};

/**
 * Load table for character kills
 */
var loadKills = function() {
    $.ajax({
        url : '/stats/kills',
        method : 'GET',
        success : function(resp) {
            $('#most-kills').empty();
            $('#most-kills').append(resp);
        },
        error : function(err) {
            console.log(err);
        }
    });
};



/**
 * Load stats for graphs
 */
var loadGraphs = function() {
    $.ajax({
        url : '/stats/data/candlestick',
        method : 'GET',
        success : function(resp) {
            drawChart(resp);
        },
        error : function(err) {
            console.log(err);
        }
    });
    $.ajax({
        url: '/stats/data/pie',
        method : 'GET',
        success : function(resp) {
            drawPieChart(resp);
        },
        error : function(err) {
            console.log(err);
        }
    });
};

var drawChart = function(respData) {
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawCandlestickChart);

    var chartData = [];

    for(var item in respData) {

        var row = respData[item];
        var newDataItem = [
            row.name,
            parseInt(row.min_level),
            parseInt(row.lower_q),
            parseInt(row.upper_q),
            parseInt(row.max_level)
        ];

        chartData.push(newDataItem);
    }



    function drawCandlestickChart() {
        var data = google.visualization.arrayToDataTable(chartData, true);

        var options = {
            title : 'Class distribution by level',
            titlePosition : 'out',
            titleTextStyle : {
                color : '#f5eBd1',
                fontSize : 18
            },
            legend : 'none',
            enableInteractivity : false,
            hAxis : {
                textStyle : {
                    color : '#f5eBd1'
                },
                title : 'Class',
                titleTextStyle : {
                    color : '#f5eBd1',
                    fontSize : 16
                }
            },
            vAxis : {
                ticks : [0, 30, 60, 90, 110],
                maxValue : 110,
                viewWindow : {
                    max : 110
                },
                textStyle : {
                    color : '#f5eBd1'
                },
                title : 'Level',
                titleTextStyle : {
                    color : '#f5eBd1',
                    fontSize : 16
                },
                gridlines : {
                    color : '#666'
                }
            },
            backgroundColor : {
                stroke : '#f5eBd1',
                fill : '#231207'
            },
            colors : ['yellow']       // specific for later lookups
        };

        var chart = new google.visualization.CandlestickChart(document.getElementById('stats-div'));

        chart.draw(data, options);

        // Apply custom styling
        var $rect = $('svg').find('rect[fill="#ffff00"]');

        var colors = [
            '#c41e3b', // Death Knight
            '#a330c9', // Demon Hunter
            '#ff7c0a', // Druid
            '#aad372', // Hunter
            '#68ccef', // Mage
            '#00ffba', // Monk
            '#f48cba', // Paladin
            'white', // Priest
            '#fff468', // Rogue
            '#2359ff', // Shaman
            '#9382c9', // Warlock
            '#c69b6d' // Warrior
        ];

        var $tooltip = $('<div></div>');
        $tooltip.addClass('chart-tooltip');
        $tooltip.css('display', 'none');
        $tooltip.css('position', 'absolute');
        $tooltip.text('Tooltip');
        $('body').append($tooltip);

        var i = 0;
        $.each($rect, function() {
            var colorIndex = Math.floor(i / 2);
            $(this).attr('fill', colors[colorIndex]);
            $(this).attr('stroke', colors[colorIndex]);
            $(this).attr('index', colorIndex);
            i++;

            $(this).mouseover(function() {
                var index = $(this).attr('index');
                var tooltipText = `${respData[index].name} (${respData[index].total}) : ${respData[index].min_level}-${respData[index].max_level} mean ${Math.round(respData[index].avg_level)}`;

                $tooltip.css('top', event.clientY + 10);
                $tooltip.css('left', event.clientX + 10);
                $tooltip.css('display', 'block');
                $tooltip.text(tooltipText);
            });

            $(this).mouseleave(function() {
                $tooltip.css('display', 'none');
            });
        });

    };
};

var drawPieChart = function(respData) {
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawPieChart);

    function drawPieChart() {

        var colorMap = {
            1 : '#c69b6d', // Warrior
            2 : '#f48cba', // Paladin
            3 : '#aad372', // Hunter
            4 : '#fff468', // Rogue
            5 : 'white', // Priest
            6 : '#c41e3b', // Death Knight
            7 : '#2359ff', // Shaman
            8 : '#68ccef', // Mage
            9 : '#9382c9', // Warlock
            10 : '#00ffba', // Monk
            11 : '#ff7c0a', // Druid
            12 : '#a330c9', // Demon Hunter
        };

        var rawData = [];
        var sliceData = {};
        rawData.push(['Class', 'Total Kills']);
        $.each(respData, function(k, v) {
            rawData.push([v.name, parseInt(v.kills)]);
            sliceData[k] = { 'color' : colorMap[ v['id_ext'] ] };
        });

        var data = google.visualization.arrayToDataTable(rawData);

        var options = {
            title: 'Kills by Class',
            titlePosition : 'out',
            titleTextStyle : {
                color : '#f5eBd1',
                fontSize : 18
            },
            backgroundColor : {
                stroke : '#f5eBd1',
                fill : '#231207'
            },
            slices : sliceData,
            legend : {
                textStyle : {
                    color : '#f5eBd1'
                }
            },
            tooltip : {
                isHtml : true
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('stats-pie-div'));

        chart.draw(data, options);
    };
};
