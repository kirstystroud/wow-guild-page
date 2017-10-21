$(document).ready(function() {
    // Update nav-bar
    updateNavBar()

    // Load characters data
    if (window.location.pathname == '/characters') {
        loadCharactersTab({ sort : 0 });
    }

    // Load status
    if (window.location.pathname == '/stats') {
        loadGraphs();
        loadDeaths();
        loadKills();
    }

    // Load dungeons
    if (window.location.pathname == '/dungeons') {
        loadDungeons();
    }

    // Load reputation
    if (window.location.pathname == '/reputation') {
        loadReputation();
    }

    attachEventHandlers();
});

/**
 * Update nav bar to have correct tab highlighted
 */
var updateNavBar = function() {
    var pathname = window.location.pathname;
    var $span = $('<span class="sr-only">(current)</span>');
    switch(pathname) {
        case '/characters' :
            $('#navbar-characters').addClass('active');
            break
        case '/dungeons' :
            $('#navbar-dungeons').addClass('active');
            break;
        case '/professions' :
            $('#navbar-professions').addClass('active');
            break;
        case '/stats' :
            $('#navbar-stats').addClass('active');
            break;
        case '/reputation' :
            $('#navbar-reputation').addClass('active');
        default :
            console.log(`Unknown path ${pathname}`);
    }
}

/**
 * Load characters table and populate with ajax data
 * @param sortData details on any sorting required
 */
var loadCharactersTab = function(sortData) {
    $.ajax({
        url : '/characters/data',
        method : 'GET',
        data : sortData,
        success : function(resp) {
            $('#guild-members-list').empty();
            $('#guild-members-list').append(resp);

            attachEventHandlers();
        },
        error : function(err) {
            console.log(err);
        }
    });
};

/**
 * Load content for dungeons page
 */
var loadDungeons = function() {
    $.ajax({
        url : '/dungeons/data',
        method : 'GET',
        success : function(resp) {
            $('#dungeons-panel-group').empty();
            $('#dungeons-panel-group').append(resp);

            $.each($('#dungeons-panel-group').find('.dungeon-panel-pending'), function() {
                var dungeonId = $(this).attr('id');
                var splitId = dungeonId.split('-');
                loadDungeonRow(splitId[2]);
            });
        },
        error : function(err) {
            console.log(err);
        }
    });
};

/**
 * Load content for single row on dungeons page
 */
var loadDungeonRow = function(id) {
    $.ajax({
        url : '/dungeons/data/' + id,
        method: 'GET',
        success : function(resp) {
            var $panel = $('#dungeon-panel-' + id);
            $panel.empty();
            $panel.append(resp.view);
            $panel.removeClass('dungeon-panel-pending');
            $panel.addClass(resp.class);
        },
        error : function(err) {
            console.log(err);
        }
    });
}

/**
 * Load content for reputations page
 */
var loadReputations = function() {

};

/**
 * Attach page event handlers
 */
var attachEventHandlers = function() {
    $('[data-toggle="tooltip"]').tooltip();

    // Sorting icons
    $('#guild-members-list th').click(function() {
        var splitId = $(this).attr('id').split('th-');
        var sortName = splitId[1]
        var order = $(this).find('span').attr('sort');

        var newSort = 'asc';
        if (order == 'asc') {
            newSort = 'desc';
        }

        var sortingInfo = {};
        sortingInfo[sortName] = newSort

        loadCharactersTab({ sort : sortingInfo });
    });

    // Showing dungeon panels on a per-char basis
    $('#dungeon-char-select').change(function() {
        var selected = $(this).val();
        if (selected && (selected != '0')) {
            $('.dungeon-panel').css('display', 'none');
            $('.char-' + selected).parents('.dungeon-panel').css('display', 'block');
        } else {
            $('.dungeon-panel').css('display', 'block');
        }
    });
};

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
}

/**
 * Load stats for graphs
 */
var loadGraphs = function() {
    $.ajax({
        url : '/stats/data',
        method : 'GET',
        success : function(resp) {
            drawChart(resp);
        },
        error : function(err) {
            console.log(err);
        }
    });
};

var drawChart = function(respData) {
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    var chartData = [];

    for(var item in respData) {

        var row = respData[item];
        var newDataItem = [
            row.name,
            row.min_level,
            row.lower_q,
            row.upper_q,
            row.max_level
        ];

        chartData.push(newDataItem);
    }



    function drawChart() {
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
