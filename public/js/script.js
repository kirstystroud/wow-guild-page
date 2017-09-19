$(document).ready(function() {
    // Load characters tab
    loadCharactersTab({ sort : 0 });

    // Load dungeons tab
    loadDungeonsTab();
});

/**
 * Load characters table and populate with ajax data
 * @param sortData details on any sorting required
 */
var loadCharactersTab = function(sortData) {
    $.ajax({
        url : '/characters',
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
 * Load data on available dungeons
 */
var loadDungeonsTab = function() {
    $.ajax({
        url : '/dungeons',
        method : 'GET',
        success : function(resp) {
            $('#guild-dungeons-available-list').empty();
            $('#guild-dungeons-available-list').append(resp);
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
