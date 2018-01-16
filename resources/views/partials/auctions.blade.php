<div>
    @include('partials.auctions-search')
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
        <table class="table">
            <thead>
                <th>Item</th>
                <th>Bid</th>
                <th>Buyout</th>
                <th>Sell Price</th>
                <th>Time Left</th>
                <th>Status</th>
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
