$(document).ready(function() {
    // Update nav-bar
    updateNavBar()

    // Load raids
    if (window.location.pathname == '/raids') {
        loadRaids();
    }

    // Load reputation
    if (window.location.pathname == '/reputation') {
        loadReputation();
    }

    // Load auctions
    if (window.location.pathname == '/auctions') {
        loadAuctions();
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
};

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
}

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
            attachEventHandlers();
        },
        error : function(err) {
            console.log(err);
        }
    });
};

/**
 * Attach page event handlers
 */
var attachEventHandlers = function() {
    $('[data-toggle="tooltip"]').tooltip();

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

                attachCompareQuestsHandler();
            },
            error : function(err) {
                console.log(err);
            }
        });
    });

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
                attachEventHandlers();
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
                attachEventHandlers();
            },
            error : function(err) {
                console.log(err);
            }
        });
    });
};

/**
 * Attach handler for comparing character quests, needs to be reattached after each request
 */
var attachCompareQuestsHandler = function() {
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

                attachCompareQuestsHandler();
            },
            error : function(err) {
                console.log(err);
            }
        });
    });
};
