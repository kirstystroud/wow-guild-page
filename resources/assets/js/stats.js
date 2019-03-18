/**
 * Class for handling all js functionality on stats page
 */
function StatsHandler() {

    /**
     * Public entry point
     */
    this.init = function() {
        loadStatsGraphs();
        loadStatsDeaths();
        loadStatsKills();
        loadStatsPvpKills();
        loadStatsDungeons();
        loadStatsRaids();
    };

    /**
     * Load table for character deaths
     */
    var loadStatsDeaths = function() {
        loadStatsInternal('/stats/deaths', $('#most-deaths'));
    };

    /**
     * Load table for character kills
     */
    var loadStatsKills = function() {
        loadStatsInternal('/stats/kills', $('#most-kills'));
    };

    /**
     * Load table for pvp kills
     */
    var loadStatsPvpKills = function() {
        loadStatsInternal('/stats/pvpkills', $('#pvp-kills'));
    };

    /**
     * Load table for dungeons entered
     */
    var loadStatsDungeons = function() {
        loadStatsInternal('/stats/dungeons', $('#dungeons-entered'));
    };

    /**
     * Load table for raids entered
     */
    var loadStatsRaids = function() {
        loadStatsInternal('/stats/raids', $('#raids-entered'));
    };

    /**
     * Internal function for loading stats table
     * @param {string} url
     * @param {Object} $container
     */
    var loadStatsInternal = function(url, $container) {
        $.ajax({
            url : url,
            method : 'GET',
            success : function(resp) {
                $container.empty();
                $container.append(resp);
            },
            error : function(err) {
                console.log(err);
            }
        });
    };

    /**
     * Load stats for graphs
     */
    var loadStatsGraphs = function() {
        // Candlestick
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
        // Kills pie chart
        $.ajax({
            url : '/stats/data/pie',
            method : 'GET',
            success : function(resp) {
                drawPieChart(resp);
            },
            error : function(err) {
                console.log(err);
            }
        });
        // Quests pie chart
        $.ajax({
            url : '/stats/data/quests',
            method : 'GET',
            success : function(resp) {
                drawQuestsPieChart(resp);
            },
            error : function(err) {
                console.log(err);
            }
        });
    };

    /**
     * Build candlestick chart from response data
     */
    var drawChart = function(respData) {
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCandlestickChart);

        var chartData = [];

        for (var item in respData) {
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

        /**
         * Draw candlestick chart for character levels
         */
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
                // specific for later lookups
                colors : ['yellow']
            };

            var chart = new google.visualization.CandlestickChart(document.getElementById('stats-div'));

            chart.draw(data, options);

            // Apply custom styling
            var $rect = $('svg').find('rect[fill="#ffff00"]');

            var colors = [
                // Death Knight
                '#c41e3b',
                // Demon Hunter
                '#a330c9',
                // Druid
                '#ff7c0a',
                // Hunter
                '#aad372',
                // Mage
                '#68ccef',
                // Monk
                '#00ffba',
                // Paladin
                '#f48cba',
                // Priest
                'white',
                // Rogue
                '#fff468',
                // Shaman
                '#2359ff',
                // Warlock
                '#9382c9',
                // Warrior
                '#c69b6d'
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
                    var tooltipText = `${respData[index].name} (${respData[index].total}) : ${respData[index].min_level} - ${respData[index].max_level} mean ${Math.round(respData[index].avg_level)}`;

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

    /**
     * Build kills pie chart from data
     */
    var drawPieChart = function(respData) {

        var rawData = [];
        rawData.push(['Class', 'Total Kills']);
        $.each(respData, function(k, v) {
            rawData.push([v.name, parseInt(v.kills)]);
        });

        var sliceData = buildSliceData(respData);

        drawPieChartFromConfig(rawData, sliceData, { title : 'Kills by Class', div : 'stats-pie-div' });
    };

    /**
     * Build quests pie chart from data
     */
    var drawQuestsPieChart = function(respData) {

        var rawData = [];
        rawData.push(['Class', 'Quests Completed']);
        $.each(respData, function(k, v) {
            rawData.push([v.name, parseInt(v.quests)]);
        });

        var sliceData = buildSliceData(respData);

        drawPieChartFromConfig(rawData, sliceData, { title : 'Quests by Class', div : 'stats-pie-quests-div' });
    };

    /**
     * Build slice data from response data
     * @param {Object} respData
     * @return {Object}
     */
    var buildSliceData = function(respData) {
        var colorMap = {
            // Warrior
            1 : '#c69b6d',
            // Paladin
            2 : '#f48cba',
            // Hunter
            3 : '#aad372',
            // Rogue
            4 : '#fff468',
            // Priest
            5 : 'white',
            // Death Knight
            6 : '#c41e3b',
            // Shaman
            7 : '#2359ff',
            // Mage
            8 : '#68ccef',
            // Warlock
            9 : '#9382c9',
            // Monk
            10 : '#00ffba',
            // Druid
            11 : '#ff7c0a',
            // Demon Hunter
            12 : '#a330c9',
        };

        var sliceData = {};
        $.each(respData, function(k, v) {
            sliceData[k] = { 'color' : colorMap[ v['id_ext'] ] };
        });

        return sliceData;
    };

    /**
     * Build generic pie chart from config
     * @param {Object} rawData
     * @param {Object} sliceData
     * @param {Object} config - required keys 'title' , 'div'
     */
    var drawPieChartFromConfig = function(rawData, sliceData, config) {

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawPieChartFromConfigInternal);

        function drawPieChartFromConfigInternal() {
            var data = google.visualization.arrayToDataTable(rawData);

            var options = {
                title: config.title,
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

            var chart = new google.visualization.PieChart(document.getElementById(config.div));
            chart.draw(data, options);
        };
    };
};
