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
