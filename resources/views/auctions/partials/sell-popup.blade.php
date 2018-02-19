<td>
    <a class="show-info" pet-id="{{ $auction->pet_id }}" data-toggle="modal" data-target="#auctions-sell-info-modal-{{ $auction->pet_id }}">View</a>
</td>
<div id="auctions-sell-info-modal-{{ $auction->pet_id }}" class="modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header wow-modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>{{ $auction->itemName() }} <p class="pull-right text-right"> {!! $auction->buyoutToGoldFormatted() !!}&nbsp&nbsp</p></h4>
            </div>
            <div class="modal-body wow-modal-body">
                @foreach($auction->getPreviouslySold() as $sold)
                    <div class="row">
                        <div class="col-md-6 col-md-offset-1">
                            <p>{!! $sold->sellPriceToGoldFormatted() !!}</p>
                        </div>
                        <div class="col-md-5">
                            <p>{{ $sold->updated_at }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
