<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;


use ProtoneMedia\Splade\Facades\Toast;

use App\Http\Requests\PostStoreRequest;
use App\Tables\Posts;

class PostController extends Controller
{
    public function index()
    {
        

        return view('posts.index', [
            // 'posts' => SpladeTable::for($posts)
            //     ->column('title',  canBeHidden: false, sortable: true)
            //     ->withGlobalSearch(columns: ['title'])
            //     ->column('slug',  sortable: true)
            //     ->column('action')
            //     ->selectFilter('category_id', $categories)
            //     ->paginate(5),

            'posts' => Posts::class,
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
