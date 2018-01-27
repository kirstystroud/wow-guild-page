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
