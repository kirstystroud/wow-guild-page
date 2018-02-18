<div id="recipe-search-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header wow-modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Recipe Finder</h4>
            </div>
            <div class="modal-body wow-modal-body">
                <form id="search-recipes-form">
                    @include('partials.filter-form.text', [ 'label' => 'Recipe Name', 'key' => 'name' ])
                    @include('partials.filter-form.select', [ 'label' => 'Profession (optional)', 'key' => 'profession', 'data' => $professions ])
                    <button type="submit" class="btn btn-search" id="wow-button-submit">Go</button>
                </form>
                <div id="search-recipes-result"></div>
            </div>
        </div>
    </div>
</div>
