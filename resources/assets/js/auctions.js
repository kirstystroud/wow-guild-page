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

        performSearch(href);
    });

    // Character select change
    $('#auction-char-select').change(function() {
        performSearch('/auctions/data');
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

    var performSearch = function(url, sort) {
        // Pull data out of form before resetting view
        var data = {
            item : $('#item').val(),
            status : $('#status').val(),
            time : $('#time').val(),
            sold : $('#sold').is(':checked'),
            active : $('#active').is(':checked'),
            character : $('#auction-char-select').val()
        };

        if (sort) {
            data.sort = sort;
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
};
