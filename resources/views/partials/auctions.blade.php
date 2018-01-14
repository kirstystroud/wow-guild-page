<div>
</div>
<div>
    <div class="auction-pagination">
        {{ $auctions->links() }}
    </div>
    <table class="table">
        <thead>
            <th>Id</th>
            <th>Item</th>
            <th>Bid</th>
            <th>Buyout</th>
            <th>Time Left</th>
            <th>Status</th>
        </thead>
        <tbody>
            @foreach($auctions as $auction)
                <tr>
                    <td>{{ $auction->id }}</td>
                    <td>{{ $auction->itemName() }}</td>
                    <td>{!! $auction->bidToGoldFormatted() !!}</td>
                    <td>{!! $auction->buyoutToGoldFormatted() !!}</td>
                    <td>{{ $auction->timeLeft() }}</td>
                    <td>{{ $auction->getStatus() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
