<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Category;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::with('category')->get();
        $categories = Category::all();

        return view('index', compact('todos', 'categories'));
    }

    public function search(Request $request)
    {
      $todos = Todo::with('category')->CategorySearch($request->category_id)->KeywordSearch($request->keyword)->get();
      $categories = Category::all();

      return view('index', compact('todos', 'categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request)
    {
        $todo = $request->only(['category_id', 'content']);
        Todo::create($todo);

        return redirect('/')->with('message', 'Todoを作成しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request)
    {
        $todo = $request->only(['content']);
        Todo::find($request->id)->update($todo);

        return redirect('/')->with('message', 'Todoを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Todo::find($request->id)->delete();
        return redirect('/')->with('message', 'Todoを削除しました');
    }
}
