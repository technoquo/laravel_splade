<?php

namespace App\Http\Controllers;

use App\Models\Category;
use ProtoneMedia\Splade\Facades\Toast;
use App\Http\Requests\CategoryStoreRequest;
use App\Tables\Categories;

class CategoryController extends Controller
{
    public function index()
    {
      

        return view('categories.index', [
            // 'categories' => SpladeTable::for(Category::class)
            //     ->column('title',  canBeHidden: false, sortable: true)
            //     ->withGlobalSearch(columns: ['title'])
            //     ->column('slug')
            //     ->column('action')
            //     ->paginate(5),
            'categories' => Categories::class,
        ]);
    }

    public function create()
    {
       return view('categories.create');
    }


    public function store(CategoryStoreRequest $request)
    {
        Toast::title('New Category Created Successfully!');
        Category::create($request->validated());

       return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryStoreRequest $request)
    {
        Toast::title('Category Updated Successfully!');
        Category::create($request->validated());

       return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        Toast::title('Category Delete Successfully!')->autoDismiss(5);
        return redirect()->back();
    }


}
