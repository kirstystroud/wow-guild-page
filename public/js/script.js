$(document).ready(function() {
    // Update nav-bar
    updateNavBar()

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
 * Attach page event handlers
 */
var attachEventHandlers = function() {
    $('[data-toggle="tooltip"]').tooltip();

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
