<form class="search-form" action="{{ Forum::route('search') }}" method="GET">
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="input-group">
                <input type="text" class="form-control search-field" placeholder="Search..." name="q" value="{{ request('q') }}">
                <div class="input-group-append">
                    <button class="btn btn-primary search-button" type="button" style="border-bottom-right-radius: 4px; border-top-right-radius: 4px">Search</button>
                </div>
                <div class="input-group-append">
                    <a class="btn btn-link" href="{{ \Illuminate\Support\Facades\URL::current() }}">Clear Search</a>
                </div>
            </div>
        </div>
    </div>
</form>
