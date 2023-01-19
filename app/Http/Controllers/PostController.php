<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\QueryBuilder;
use ProtoneMedia\Splade\Facades\Toast;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\PostStoreRequest;

class PostController extends Controller
{
    public function index()
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('title', 'LIKE', "%{$value}%")
                        ->orWhere('slug', 'LIKE', "%{$value}%");
                });
            });
        });


        $posts = QueryBuilder::for(Post::class)
            ->defaultSort('title')
            ->allowedSorts(['title', 'slug'])
            ->allowedFilters(['title', 'slug', 'category_id', $globalSearch]);

        $categories = Category::pluck('title', 'id')->toArray();

        return view('posts.index', [
            'posts' => SpladeTable::for($posts)
                ->column('title',  canBeHidden: false, sortable: true)
                ->withGlobalSearch(columns: ['title'])
                ->column('slug',  sortable: true)
                ->column('action')
                ->selectFilter('category_id', $categories)
                ->paginate(5),
        ]);
    }

    public function create()
    {
        $categories = Category::pluck('title', 'id')->toArray();
       return view('posts.create', compact('categories'));
    }

    public function store(PostStoreRequest $request)
    {
       Toast::title('New Post Created Successfully!');
       Post::create($request->validated());

       return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        $categories = Category::pluck('title', 'id')->toArray();
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(PostStoreRequest $request, Post $post)
    {
        $post->update($request->validated());
        Toast::title('Post Updated Successfully!')->autoDismiss(5);
       

       return to_route('posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        Toast::title('Post Delete Successfully!')->autoDismiss(5);
        return redirect()->back();
    }

}
