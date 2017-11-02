<a href="{{ $character->getLinkAddr() }}" target="_blank" class="char-link char-link-{{ $character->character_class->id_ext }}">


@if(isset($title))
    {{ $char->getTitle() }}
@else
    {{ $character->name }}
@endif
</a> 

@if( !isset($omitLevel) )
    <span>({{ $character->level }})</span>
@endif
