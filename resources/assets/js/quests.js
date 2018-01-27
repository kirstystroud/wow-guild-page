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
