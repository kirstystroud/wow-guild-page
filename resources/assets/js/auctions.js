$(document).ready(function() {
    // Load auctions
    if (window.location.pathname == '/auctions') {
        // attachOneOffEventHandlers();
        loadAuctions();
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
            attachOneOffEventHandlers();
        },
        error : function(err) {
            console.log(err);
        }
    });
};

/**
 * Attach event handlers which should only be attached once
 */
var attachOneOffEventHandlers = function() {
    // Character select change
    $('#auction-char-select').change(function() {
        performSearch('/auctions/data');
    });

    attachAuctionsEventHandlers();
};

/**
 * Attach event handlers which need to be attached after each data update
 */
var attachAuctionsEventHandlers = function() {
    // Set pagination links on auction page to make ajax not redirect to page
    $('.pagination a').click(function() {
        var href = $(this).attr('href');
        $(this).removeAttr('href');

        performSearch(href);
    });

    // Auctions searching
    $('#wow-button-auctions-search').click(function(event) {
        performSearch('/auctions/data');
    });

    // Auctions filtering
    $('.table-sort').click(function(event) {
        var splitId = $(this).parent().attr('id').split('th-');
        var sortName = splitId[1]
        var order = $(this).find('span').attr('sort');

        var newSort = 'asc';
        if (order == 'asc') {
            newSort = 'desc';
        }

        var sortingInfo = {};
        sortingInfo[sortName] = newSort;
        performSearch('/auctions/data', sortingInfo)
    });

};

// Get current sort information
var getCurrentSort = function() {
    // Do we have sort icon already there
    var $sortIcon = $('.wow-sort');
    if (!$sortIcon.length) return false;

    // Pull out sort attributes
    var key = $sortIcon.attr('key');
    var direction = $sortIcon.attr('sort');

    // Build up sort data
    var sortingInfo = {};
    sortingInfo[key] = direction;
    return sortingInfo;
};

var performSearch = function(url, sort) {
    // Pull data out of form before resetting view
    var data = {
        item : $('#item').val(),
        status : $('#status').val(),
        time : $('#time').val(),
        sold : $('#sold').is(':checked'),
        active : $('#active').is(':checked'),
        cheapest : $('#cheapest').is(':checked'),
        notowned : $('#notowned').is(':checked'),
        character : $('#auction-char-select').val()
    };

    if (sort) {
        data.sort = sort;
    } else {
        var currentSorting = getCurrentSort();
        if (currentSorting) data.sort = currentSorting;
    }

    // Show loading screen if not doing pagination
    if (url == '/auctions/data') {
        $('#auctions-search-modal').modal('hide');
        $('#table-container').empty();
        $('#table-container').append('Loading ...');
    }

    $.ajax({
        url : url,
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
};
