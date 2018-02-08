<div>
    @include('auctions.partials.search')
</div>
<div>
    @if(!$auctions->count())
        <br>
        <div>No results found</div>
    @else
        <div>
            <div class="auction-pagination">
                {{ $auctions->links() }}
                <p class="float-right pull-right">Showing auctions {{ $auctions->firstItem() }} to {{ $auctions->lastItem() }} of {{ $auctions->total() }}</p>
            </div>
        </div>
        <div id="table-container">
            <table class="table table-hover">
                <thead>
                    @include('partials.th-sort-heading', [ 'label' => 'Item', 'key' => 'item' ])
                    @include('partials.th-sort-heading', [ 'label' => 'Bid', 'key' => 'bid' ])
                    @include('partials.th-sort-heading', [ 'label' => 'Buyout', 'key' => 'buyout' ])
                    @include('partials.th-sort-heading', [ 'label' => 'Sell Price', 'key' => 'sell_price' ])
                    @include('partials.th-sort-heading', [ 'label' => 'Time Left', 'key' => 'time_left' ])
                    @include('partials.th-sort-heading', [ 'label' => 'Status', 'key' => 'status' ])
                    <th>Owned</th>
                </thead>
                <tbody>
                    @foreach($auctions as $auction)
                        <tr>
                            <td>{{ $auction->itemName() }}</td>
                            <td>{!! $auction->bidToGoldFormatted() !!}</td>
                            <td>{!! $auction->buyoutToGoldFormatted() !!}</td>
                            <td>{!! $auction->sellPriceToGoldFormatted() !!}</td>
                            <td>{{ $auction->timeLeft() }}</td>
                            <td>{{ $auction->getStatus() }}</td>
                            @if($character)
                                @if( $character->doesOwnPet($auction->pet_id) )
                                    <td class="td-yes">Yes</td>
                                @else
                                    <td class="td-no">No</td>
                                @endif
                            @else
                                <td>-</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
