<form class="search-form" action="{{ Forum::route('search') }}" method="GET">
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="input-group">
                <input type="text" class="form-control search-field" placeholder="Search..." name="q" value="{{ request('q') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary search-button" type="button">Search</button>
                </div>
            </div>
        </div>
    </div>
</form>
