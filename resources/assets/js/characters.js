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
        sortingInfo[sortName] = newSort;

        loadCharactersTab({ sort : sortingInfo });
    });

};
