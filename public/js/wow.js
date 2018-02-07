$(document).ready(function() {
    // Load auctions
    if (window.location.pathname == '/auctions') {
        loadAuctions();
        attachAuctionsEventHandlers();
    }
});

/**
 * Load content for auctions page
 */
var loadAuctions = function() {
    $.ajax({
        url : '/auctions/data',
        method : 'GET',
        success : function(resp) {
            $('#auctions-panel').empty();
            $('#auctions-panel').append(resp);
            attachAuctionsEventHandlers();
        },
        error : function(err) {
            console.log(err);
        }
    });
};

var attachAuctionsEventHandlers = function() {
    // Set pagination links on auction page to make ajax not redirect to page
    $('.pagination a').click(function() {
        var href = $(this).attr('href');
        $(this).removeAttr('href');

        // Pull data out of form
        var data = {
            item : $('#item').val(),
            status : $('#status').val(),
            time : $('#time').val(),
            sold : $('#sold').is(':checked')
        };

        $.ajax({
            url : href,
            method : 'GET',
            data : data,
            success : function(resp) {
                $('#auctions-panel').empty();
                $('#auctions-panel').append(resp);
                attachAuctionsEventHandlers();
            },
            error : function(err) {
                console.log(err);
            }
        });
    });

    // Auctions searching
    $('#wow-button-auctions-search').click(function(event) {

        // Pull data out of form before resetting view
        var data = {
            item : $('#item').val(),
            status : $('#status').val(),
            time : $('#time').val(),
            sold : $('#sold').is(':checked')
        };

        $('#auctions-search-modal').modal('hide');
        $('#auctions-panel').empty();
        $('#auctions-panel').append('Loading ...');
        $.ajax({
            url : '/auctions/data',
            method : 'GET',
            data : data,
            success : function(resp) {
                $('#auctions-panel').empty();
                $('#auctions-panel').append(resp);
                attachAuctionsEventHandlers();
            },
            error : function(err) {
                console.log(err);
            }
        });
    });
};

$(document).ready(function() {
    // Load characters data
    if (window.location.pathname == '/characters') {
        loadCharactersTab();
    }
});

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

            attachCharacterEventHandlers();
        },
        error : function(err) {
            console.log(err);
        }
    });
};

var attachCharacterEventHandlers = function() {
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

};

$(document).ready(function() {
    // Load dungeons
    if (window.location.pathname == '/dungeons') {
        loadDungeons();
        attachDungeonEventHandlers();
    }
});

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

            $.each($('#dungeons-panel-group').find('.panel-pending'), function() {
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
            $panel.removeClass('panel-pending');
            $panel.addClass(resp.class);
        },
        error : function(err) {
            console.log(err);
        }
    });
};

var attachDungeonEventHandlers = function() {
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

$(document).ready(function() {
    // Update nav-bar
    var pathname = window.location.pathname;
    var $span = $('<span class="sr-only">(current)</span>');
    switch(pathname) {
        case '/characters' :
            $('#navbar-characters').addClass('active');
            break
        case '/dungeons' :
            $('#navbar-dungeons').addClass('active');
            break;
        case '/raids' :
            $('#navbar-raids').addClass('active');
            break;
        case '/professions' :
            $('#navbar-professions').addClass('active');
            break;
        case '/stats' :
            $('#navbar-stats').addClass('active');
            break;
        case '/reputation' :
            $('#navbar-reputation').addClass('active');
            break;
        case '/quests' :
            $('#navbar-quests').addClass('active');
            break;
        case '/auctions' :
            $('#navbar-auctions').addClass('active');
            break;
        default :
            console.log(`Unknown path ${pathname}`);
    }
});

$(document).ready(function() {
    if (window.location.pathname == '/professions') {
        attachProfessionsEventHandlers();
    }
});

var attachProfessionsEventHandlers = function() {
    // Submitting profession search form
    $('#wow-button-submit').click(function(event) {
        event.preventDefault();
        $('#search-recipes-result').empty();
        $('#search-recipes-result').append('<br>', '<p class="wow-searching">Searching...</p>');

        var formData = {
            name : $('#name').val(),
            profession : $('#profession').val()
        };
        $.ajax({
            url : '/professions/search',
            data : formData,
            method : 'GET',
            success : function(resp) {
                $('#search-recipes-result').empty();
                $('#search-recipes-result').append(resp);
            },
            error : function(err) {
                console.log(err);
            }
        });
    });
};

$(document).ready(function() {
    if (window.location.pathname == '/quests') {
        setupQuestSearch();
    }
});

var setupQuestSearch = function() {
    // Quests searching
    $('#wow-button-submit-quests').click(function(event) {
        event.preventDefault();
        $('#quest-results').empty();
        $('#quest-results').append('<br>', '<p class="wow-searching">Searching...</p>');

        var data = {
            character : $('#quests-characters-select').val(),
            category : $('#quests-categories-select').val()
        };
        $.ajax({
            url : '/quests/search',
            data : data,
            method : 'GET',
            success : function(resp) {
                $('#quest-results').empty();
                $('#quest-results').append(resp);

                // Attach event handlers to links
                $('.td-category').click(function(event) {
                    event.preventDefault();
                    $('#quests-categories-select').val($(this).attr('category-id'));
                    $('#quests-characters-select').val($(this).attr('character-id'));
                    $('#wow-button-submit-quests').click();
                });

                attachQuestsEventHandlers();
            },
            error : function(err) {
                console.log(err);
            }
        });
    });
};

var attachQuestsEventHandlers = function() {
    // Quests character compare
    $('#wow-button-submit-quests-compare').click(function(event) {
        event.preventDefault();
        var data = {
            character : $('#quests-characters-select').val(),
            category : $('#quests-categories-select').val(),
            compare : $('#quests-compare-characters-select').val()
        };

        $('#quest-results').empty();
        $('#quest-results').append('<br>', '<p class="wow-searching">Searching...</p>');

        $.ajax({
            url : '/quests/search',
            data : data,
            method : 'GET',
            success : function(resp) {
                $('#quest-results').empty();
                $('#quest-results').append(resp);

                // Attach event handlers to links
                $('.td-category').click(function(event) {
                    event.preventDefault();
                    $('#quests-categories-select').val($(this).attr('category-id'));
                    $('#quests-characters-select').val($(this).attr('character-id'));
                    $('#wow-button-submit-quests').click();
                });

                attachQuestsEventHandlers();
            },
            error : function(err) {
                console.log(err);
            }
        });
    });
};

$(document).ready(function() {
    // Load raids
    if (window.location.pathname == '/raids') {
        loadRaids();
        addRaidsEventHandlers();
    }
});

/**
 * Load content for raids page
 */
var loadRaids = function() {
    $.ajax({
        url : '/raids/data',
        method : 'GET',
        success : function(resp) {
            $('#raids-panel-group').empty();
            $('#raids-panel-group').append(resp);

            $.each($('#raids-panel-group').find('.panel-pending'), function() {
                var raidId = $(this).attr('id');
                var splitId = raidId.split('-');
                loadRaidRow(splitId[2]);
            });
        },
        error : function(err) {
            console.log(err);
        }
    });
};

/**
 * Load content for a single row on the raids page
 */
var loadRaidRow = function(id) {
    $.ajax({
        url : '/raids/data/' + id,
        method : 'GET',
        success : function(resp) {
            var $panel = $('#raid-panel-' + id);
            $panel.empty();
            $panel.append(resp.view);
            $panel.removeClass('panel-pending');
            $panel.addClass(resp.class);
        },
        error : function(err) {
            console.log(err);
        }
    });
};

var addRaidsEventHandlers = function() {
    // Showing raid panels on a per-char basis
    $('#raid-char-select').change(function() {
        var selected = $(this).val();
        if (selected && (selected != '0')) {
            $('.dungeon-panel').css('display', 'none');
            $('.char-' + selected).parents('.dungeon-panel').css('display', 'block');
        } else {
            $('.dungeon-panel').css('display', 'block');
        }
    });
};

$(document).ready(function() {
    // Load reputation
    if (window.location.pathname == '/reputation') {
        loadReputation();
        attachReputationEventHandlers();
    }
});

/**
 * Load content for reputations page
 */
var loadReputation = function() {
    $.ajax({
        url : '/reputation/data',
        method : 'GET',
        success : function(resp) {
            $('#reputations-panel-group').empty();
            $('#reputations-panel-group').append(resp);

            // Loop over and pull in data
            $.each($('#reputations-panel-group').find('.panel-pending'), function() {
                var factionId = $(this).attr('id');
                var splitId = factionId.split('-');
                loadReputationRow(splitId[2]);
            });
        },
        error : function(err) {
            console.log(err);
        }
    });
};

/**
 * Load content for a single row on the reputations page
 */
var loadReputationRow = function(id) {
    $.ajax({
        url : '/reputation/data/' + id,
        method: 'GET',
        success : function(resp) {
            var $panel = $('#reputation-panel-' + id);
            $panel.empty();
            $panel.append(resp.view);
            $panel.removeClass('panel-pending');
            $panel.addClass(resp.class);
        },
        error : function(err) {
            console.log(err);
        }
    });
};

var attachReputationEventHandlers = function() {
    // Showing reputation panels on a per-char basis
    $('#reputation-char-select').change(function() {
        var selected = $(this).val();
        if (selected && (selected != '0')) {
            $('.reputation-panel').css('display', 'none');
            $('.char-' + selected).parents('.reputation-panel').css('display', 'block');
        } else {
            $('.reputation-panel').css('display', 'block');
        }
    });
};

$(document).ready(function() {
    // Load status
    if (window.location.pathname == '/stats') {
        loadStatsGraphs();
        loadStatsDeaths();
        loadStatsKills();
        loadStatsDungeons();
        loadStatsRaids();
    }
});

/**
 * Load table for character deaths
 */
var loadStatsDeaths = function() {
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
var loadStatsKills = function() {
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
 * Load table for dungeons entered
 */
var loadStatsDungeons = function() {
    $.ajax({
        url : '/stats/dungeons',
        method : 'GET',
        success : function(resp) {
            $('#dungeons-entered').empty();
            $('#dungeons-entered').append(resp);
        },
        error : function(err) {
            console.log(err);
        }
    });
};

/**
 * Load table for raids entered
 */
var loadStatsRaids = function() {
    $.ajax({
        url : '/stats/raids',
        method : 'GET',
        success : function(resp) {
            $('#raids-entered').empty();
            $('#raids-entered').append(resp);
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

$(document).ready(function() {

    $.ajax({
        url : '/tabard',
        method : 'GET',
        success : function(resp) {
            var tabard = new GuildTabard(JSON.parse(resp));
        },
        error : function(err) {
            console.log(err);
        }
    });

    function GuildTabard(guildMeta) {

        var canvas = document.getElementById('guild-tabard');
        var context = canvas.getContext('2d');

        var self = this,
            _width = canvas.width,
            _height = canvas.height,
            _src = [
                '/images/ring.png',
                '/images/shadow.png',
                '/images/background.png',
                '/images/overlay.png',
                '/images/border.png',
                '/images/emblem.png',
                '/images/hooks.png'
            ],

            // Colors that need to be applied to each layer
            _color = [
                null,
                null,
                guildMeta['background_color_data'],
                null,
                guildMeta['border_color_data'],
                guildMeta['icon_color_data'],
                null
            ],

            // Positions to overlay each image at
            _position = [
                [ 0, 0, (_width*216/240), (_width*216/240) ],
                [ (_width*18/240), (_width*27/240), (_width*179/240), (_width*216/240) ],
                [ (_width*18/240), (_width*27/240), (_width*179/240), (_width*210/240) ],
                [ (_width*18/240), (_width*27/240), (_width*179/240), (_width*210/240) ],
                [ (_width*31/240), (_width*40/240), (_width*147/240), (_width*159/240) ],
                [ (_width*33/240), (_width*57/240), (_width*125/240), (_width*125/240) ],
                [ (_width*18/240), (_width*27/240), (_width*179/240), (_width*32/240) ]
            ],
            _img = [ new Image(), new Image(), new Image(), new Image(), new Image(), new Image(), new Image() ];
            $(canvas).css('opacity', 0);
        ;

        self.drawImage = function() {

            // Draw onto canvas
            _img[0].src = _src[0];
            context.drawImage(_img[0], _position[0][0], _position[0][1], _position[0][2], _position[0][3]);

            $(canvas).animate({opacity: 1}, 400);
        };

        function _render(index) {
            var _oldCanvas = new Image(),
                _newCanvas = new Image();

            // Load in contents behind new layer
            _img[index].src = _src[index];

            _img[index].onload = function() {
                _oldCanvas.src = canvas.toDataURL('image/png');
            };

            _oldCanvas.onload = function() {
                canvas.width = 1;
                canvas.width = _width;
                context.drawImage(_img[index], _position[index][0], _position[index][1], _position[index][2], _position[index][3]);

                if (typeof _color[index] !== 'undefined' && _color[index] !== null) {
                    _colorize(_color[index][0], _color[index][1], _color[index][2]);
                }

                _newCanvas.src = canvas.toDataURL('image/png');
                context.drawImage(_oldCanvas, 0, 0, _width, _height);
            };

            _newCanvas.onload = function() {
                context.drawImage(_newCanvas, 0, 0, _width, _height);
                index++;

                if (index < _src.length) {
                    _render(index);
                } else {
                    $(canvas).animate({opacity: 1}, 400);
                }
            };
        };

        function _colorize(r, g, b) {
            var imageData = context.getImageData(0, 0, _width, _height),
                pixelData = imageData.data,
                i = pixelData.length,
                intensityScale = 19,
                blend = 1 / 3,
                added_r = r / intensityScale + r * blend,
                added_g = g / intensityScale + g * blend,
                added_b = b / intensityScale + b * blend,
                scale_r = r / 255 + blend,
                scale_g = g / 255 + blend,
                scale_b = b / 255 + blend;

            do {
                if (pixelData[i + 3] !== 0) {
                    pixelData[i] = pixelData[i] * scale_r + added_r;
                    pixelData[i + 1] = pixelData[i + 1] * scale_g + added_g;
                    pixelData[i + 2] = pixelData[i + 2] * scale_b + added_b;
                }
            } while (i -= 4);
            context.putImageData(imageData, 0, 0);
        };

        _render(0);
    };
});

//# sourceMappingURL=wow.js.map