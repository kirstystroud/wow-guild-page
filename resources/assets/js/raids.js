/**
 * Class for handling js operations on raids page
 */
function RaidHandler() {

    /**
     * Public entry points
     */
    this.init = function() {
        addRaidsEventHandlers();
        loadRaids();
    };

    /**
     * Load content for raids page
     */
    var loadRaids = function() {
        $.ajax({
            url : '/raids/data',
            method : 'GET',
            success : function(resp) {
                $('#raids-panel-group').empty();
                $('#raids-panel-group').append(resp);

                $.each($('#raids-panel-group').find('.panel-pending'), function() {
                    var raidId = $(this).attr('id');
                    var splitId = raidId.split('-');
                    loadRaidRow(splitId[2]);
                });
            },
            error : function(err) {
                console.log(err);
            }
        });
    };

    /**
     * Load content for a single row on the raids page
     */
    var loadRaidRow = function(id) {
        $.ajax({
            url : '/raids/data/' + id,
            method : 'GET',
            success : function(resp) {
                var $panel = $('#raid-panel-' + id);
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

    /**
     * Attach required event handlers
     */
    var addRaidsEventHandlers = function() {
        // Showing raid panels on a per-char basis
        $('#raid-char-select').change(function() {
            var selected = $(this).val();
            if (selected && (selected != '0')) {
                $('.dungeon-panel').css('display', 'none');
                $('.char-' + selected).parents('.dungeon-panel').css('display', 'block');
            } else {
                $('.dungeon-panel').css('display', 'block');
            }
        });
    };
};
