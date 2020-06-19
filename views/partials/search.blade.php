<form action="{{ Forum::route('index') }}" method="GET">
    <div class="row mb-3">
        <div class="col-sm-12">
            <input type="text" class="form-control" placeholder="Search..." name="q" value="{{ request('q') }}">
        </div>
    </div>
</form>
