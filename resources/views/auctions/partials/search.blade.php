<button class="btn btn-search" data-toggle="modal" data-target="#auctions-search-modal">Search Auctions</button>
<div id="auctions-search-modal" class="modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header wow-modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Search Auctions</h4>
            </div>
            <div class="modal-body wow-modal-body">
                <form id="search-auctions-form">
                    @include('partials.filter-form.text', [ 'label' => 'Item', 'key' => 'item' ])
                    <div class="checkbox">
                        @include('partials.filter-form.checkbox', [ 'label' => 'Sold', 'key' => 'sold'])
                        @include('partials.filter-form.checkbox', [ 'label' => 'Active', 'key' => 'active' ])
                        @include('partials.filter-form.checkbox', [ 'label' => 'Cheapest', 'key' => 'cheapest' ])
                    </div>
                    @include('partials.filter-form.select', [ 'label' => 'Only not owned by', 'key' => 'notowned', 'data' => $characters ])
                    @include('partials.filter-form.select', [ 'label' => 'Status', 'key' => 'status', 'data' => Auction::getStatuses(), 'default' => Auction::STATUS_UNKNOWN ])
                    @include('partials.filter-form.select', [ 'label' => 'Time Remaining', 'key' => 'time', 'data' => Auction::getTimeRemaining(), 'default' => Auction::TIME_LEFT_UNKNOWN ])
                </form>
                <button type="submit" class="btn btn-search" id="wow-button-auctions-search" data-dismiss="modal">Go</button>
            </div>
        </div>
    </div>
</div>
