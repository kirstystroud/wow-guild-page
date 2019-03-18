/**
 * Class to handle all functionality related to auctions page
 */
function AuctionHandler() {

    this.init = function() {
        loadAuctions();
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
                attachAuctionsEventHandlers();
            },
            error : function(err) {
                console.log(err);
            }
        });
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

    /**
     * Get current sorting information
     * @return {Object}
     */
    var getCurrentSort = function() {
        // Do we have sort icon already there
        var $sortIcon = $('.wow-sort');
        if (!$sortIcon.length) {
            return false;
        }

        // Pull out sort attributes
        var key = $sortIcon.attr('key');
        var direction = $sortIcon.attr('sort');

        // Build up sort data
        var sortingInfo = {};
        sortingInfo[key] = direction;
        return sortingInfo;
    };

    /**
     * Perform ajax search
     * @param {string} url
     * @param {array} sort
     */
    var performSearch = function(url, sort) {
        // Pull data out of form before resetting view
        var data = {
            item : $('#item').val(),
            status : $('#status').val(),
            time : $('#time').val(),
            sold : $('#sold').is(':checked'),
            active : $('#active').is(':checked'),
            cheapest : $('#cheapest').is(':checked'),
            notowned : $('#notowned').val()
        };

        if (sort) {
            data.sort = sort;
        } else {
            var currentSorting = getCurrentSort();
            if (currentSorting) {
                data.sort = currentSorting;
            }
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
