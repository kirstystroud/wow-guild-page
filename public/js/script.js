$(document).ready(function() {
    // Load characters tab
    loadCharactersTab();

    // Load dungeons tab
    loadDungeonsTab();
});

var loadCharactersTab = function() {
    $.ajax({
        url : '/characters',
        method : 'GET',
        success : function(resp) {
            $('#guild-members-list').empty();
            $('#guild-members-list').append(resp);
        },
        error : function(err) {
            console.log(err);
        }
    });
};

var loadDungeonsTab = function() {

};