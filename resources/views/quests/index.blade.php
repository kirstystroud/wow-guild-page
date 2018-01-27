@extends('welcome')

@section('content')
    <div class="panel panel-main" id="tab-quests">
        <div class="panel-heading">
            <h4>Quests</h4>
        </div>
        <div class="panel-body">
            <div id="quest-contents">
                <div id="quest-search" class="wow-filter-form">
                    <label for="#quests-characters-select">Character</label>
                    <select id="quests-characters-select">
                        <option value="0">Select Character...</option>
                        @foreach($characters as $char)
                            <option value="{{ $char->id }}">{{ $char->name }}</option>
                        @endforeach
                    </select>
                    &nbsp
                    <label for="#quests-categories-select">Category</label>
                    <select id="quests-categories-select">
                        <option value="0">Select Category...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-search" id="wow-button-submit-quests">Search</button>
                    &nbsp
                    <span class="wow-help-icon" data-toggle="modal" data-target="#quests-help-modal">?</span>
                    @include('quests.partials.help')
                </div>
                <hr>
                <div id="quest-results">
                </div>
            </div>
        </div>
    </div>
@endsection
