<div id="recipe-search-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header wow-modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Recipe Finder</h4>
            </div>
            <div class="modal-body wow-modal-body">
                <form id="search-recipes-form">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input class="form-control" type="text" name="name" id="name" placeholder="Recipe name"></input>
                    </div>
                    <div class="form-group">
                        <label for="profession">Profession (optional)</label>
                        <select id="profession" class="form-control" name="profession">
                            <option value="0">-</option>
                                @foreach($professions as $profession)
                                    <option value="{{ $profession->id }}">{{ $profession->name }}</option>
                                @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-search" id="wow-button-submit">Go</button>
                </form>
                <div id="search-recipes-result"></div>
            </div>
        </div>
    </div>
</div>
