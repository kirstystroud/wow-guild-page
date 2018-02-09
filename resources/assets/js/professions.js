/**
 * Class for handling all js functionality on professions page
 */
function ProfessionsHandler() {

    /**
     * Public entry point
     */
    this.init = function() {
        attachProfessionsEventHandlers();
    };

    /**
     * Attach required event handlers
     */
    var attachProfessionsEventHandlers = function() {
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
    };
}
