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
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" class="form-control" name="status">
                            <option value="{{ Auction::STATUS_UNKNOWN }}">Choose Status ....</option>
                            @foreach(Auction::getStatuses() as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <button type="submit" class="btn btn-search" id="wow-button-auctions-search" data-dismiss="modal">Go</button>
            </div>
        </div>
    </div>
</div>
