{{-- $category is passed as NULL to the master layout view to prevent it from showing in the breadcrumbs --}}
<?php
$data = [];
$base_controller = new App\Http\Controllers\BaseController();
$data = $base_controller->data;
$data['current_user'] = auth()->user();
?>
@extends('layouts.homepage', $data)

@section('content-header')
    <h2 class="mt-2 mb-2 text-light"> Forum </h2>
@endsection

@section ('content-body')
    <div class="forum-wrapper">
        @include ('forum::partials.breadcrumbs')
        @include('forum::partials.search')
        @include ('forum::partials.alerts')
        @can ('createCategories')
            @include ('forum::category.partials.form-create')
        @endcan
        <div class="search-content">
            @foreach ($categories as $category)
                <table class="table table-index">
                    <thead>
                    <tr>
                        <th>{{ trans_choice('forum::categories.category', 1) }}</th>
                        <th>{{ trans_choice('forum::threads.thread', 2) }}</th>
                        <th>{{ trans_choice('forum::posts.post', 2) }}</th>
                        <th>{{ trans('forum::threads.newest') }}</th>
                        <th>{{ trans('forum::posts.last') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="category">
                        @include ('forum::search.partials.list', ['titleClass' => 'lead'])
                    </tr>
                    @if (!$category->children->isEmpty())
                        <tr>
                            <th colspan="5">{{ trans('forum::categories.subcategories') }}</th>
                        </tr>
                        @foreach ($category->children as $subcategory)
                            @include ('forum::search.partials.list', ['category' => $subcategory])
                        @endforeach
                    @endif
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
@stop

@section ('forum_scripts')
    @include('forum::partials.search-scripts')
@endsection
