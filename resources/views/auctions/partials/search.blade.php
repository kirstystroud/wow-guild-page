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
                        <label for="item">Item</label>
                        @if(isset($filters['item']) && $filters['item'])
                            <input type="text" id="item" class="form-control" value="{{ $filters['item'] }}"></input>
                        @else
                            <input type="text" id="item" class="form-control"></input>
                        @endif
                    </div>
                    <div class="checkbox">
                        <label for="sold" class="checkbox-inline">
                            @if(isset($filters['sold']) && ($filters['sold']) && ($filters['sold'] != 'false'))
                                <input type="checkbox" id="sold" checked="checked">Sold</input>
                            @else
                                <input type="checkbox" id="sold">Sold</input>
                            @endif
                        </label>
                        <label for="active" class="checkbox-inline">
                            @if(isset($filters['active']) && ($filters['active']) && ($filters['active'] != 'false'))
                                <input type="checkbox" id="active" checked="checked">Active</input>
                            @else
                                <input type="checkbox" id="active">Active</input>
                            @endif
                        </label>
                        <label for="cheapest" class="checkbox-inline">
                            @if(isset($filters['cheapest']) && ($filters['cheapest']) && ($filters['cheapest'] != 'false'))
                                <input type="checkbox" id="cheapest" checked="checked">Cheapest</input>
                            @else
                                <input type="checkbox" id="cheapest">Cheapest</input>
                            @endif
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" class="form-control" name="status">
                            <option value="{{ Auction::STATUS_UNKNOWN }}">Select ...</option>
                            @foreach(Auction::getStatuses() as $key => $value)
                                @if(isset($filters['status']) && ($filters['status'] == $key))
                                    <option value="{{ $key }}" selected="selected">{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="time">Time Remaining</label>
                        <select id="time" class="form-control" name="time">
                            <option value="{{ Auction::TIME_LEFT_UNKNOWN }}">Select ...</option>
                            @foreach(Auction::getTimeRemaining() as $key => $value)
                                @if(isset($filters['time']) && ($filters['time'] == $key))
                                    <option value="{{ $key }}" selected="selected">{{ $value }}</option>
                                @else
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </form>
                <button type="submit" class="btn btn-search" id="wow-button-auctions-search" data-dismiss="modal">Go</button>
            </div>
        </div>
    </div>
</div>
