/**
 * Main JS entry point for all pages
 */
$(document).ready(function() {
    switch (window.location.pathname) {
        case '/auctions':
            var ah = new AuctionHandler().init();
        break;

        case '/dungeons':
            var dh = new DungeonHandler().init();
        break;

        case '/characters':
            var ch = new CharactersHandler().init();
        break;

        case '/professions':
            var ph = new ProfessionsHandler().init();
        break;

        case '/quests':
            var qh = new QuestHandler().init();
        break;

        case '/raids':
            var rh = new RaidHandler().init();
        break;

        case '/reputation':
            var rh = new ReputationHandler().init();
        break;

        case '/stats':
            var sh = new StatsHandler().init();
        break;

        default:
            console.log(`Unknown location ${window.location.pathname}`);
    }
});
