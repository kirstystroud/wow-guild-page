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
