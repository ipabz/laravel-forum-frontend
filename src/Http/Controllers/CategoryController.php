<?php namespace Riari\Forum\Frontend\Http\Controllers;

use Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Riari\Forum\Frontend\Events\UserViewingCategory;
use Riari\Forum\Frontend\Events\UserViewingIndex;

class CategoryController extends BaseController
{
    /**
     * GET: Return an index of categories view (the forum index).
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->input('q');

        $params = [
            'orderBy' => 'weight',
            'orderDir' => 'asc',
            'with' => [
                'categories' => function($query) use ($keyword) {
                    if ($keyword) {
                        $query->search($keyword);
                    }
                },
                'threads'
            ],
            'search_keyword' => $keyword
        ];

        if (!$keyword) {
            $params['where'] = ['category_id' => 0];
        }

        $categories = $this->api('category.index')
                           ->parameters($params)
                           ->get();

        event(new UserViewingIndex);

        return view('forum::category.index', compact('categories'));
    }

    /**
     * GET: Search
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $keyword = $request->input('q');

        $params = [
            'orderBy' => 'weight',
            'orderDir' => 'asc',
            'with' => [
                'categories' => function($query) use ($keyword) {
                    if ($keyword) {
                        $query->search($keyword);
                    }
                },
                'threads' => function($query) use ($keyword) {
                    if ($keyword) {
                        $query->search($keyword);
                    }
                }
            ],
            'search_keyword' => $keyword
        ];

        if (!$keyword) {
            $params['where'] = ['category_id' => 0];
        }

        $categories = $this->api('search.index')
            ->parameters($params)
            ->get();

        event(new UserViewingIndex);

        return view('forum::search.index', compact('categories'));
    }

    /**
     * GET: Return a category view.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $category = $this->api('category.fetch', $request->route('category'))->get();

        event(new UserViewingCategory($category));

        $categories = [];
        if (Gate::allows('moveCategories')) {
            $categories = $this->api('category.index')->parameters(['where' => ['category_id' => 0]])->get();
        }

        $threads = $category->threadsPaginated;

        return view('forum::category.show', compact('categories', 'category', 'threads'));
    }

    /**
     * POST: Store a new category.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $category = $this->api('category.store')->parameters($request->all())->post();

        Forum::alert('success', 'categories.created');

        return redirect(Forum::route('category.show', $category));
    }

    /**
     * PATCH: Update a category.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $action = $request->input('action');

        $category = $this->api("category.{$action}", $request->route('category'))->parameters($request->all())->patch();

        Forum::alert('success', 'categories.updated', 1);

        return redirect(Forum::route('category.show', $category));
    }

    /**
     * DELETE: Delete a category.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $this->api('category.delete', $request->route('category'))->parameters($request->all())->delete();

        Forum::alert('success', 'categories.deleted', 1);

        return redirect(config('forum.routing.prefix'));
    }
}
