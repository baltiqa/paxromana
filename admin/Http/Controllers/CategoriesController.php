<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Category;
use Kris\LaravelFormBuilder\FormBuilder;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }
        $categories = Category::orderBy('order', 'ASC')->nested()->get();
        $data['categories'] = flatten($categories, 0);
        return view('panel::categories.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        $dropdown = Category::attr(['name' => 'parent_id', 'class'=>  'form-control'])->placeholder(0, '--SELECT--')->nested()->renderAsDropdown();
        $form = $formBuilder->create('Modules\Panel\Forms\CategoryForm', [
           'method' => 'POST',
           'url' => route('panel.categories.store')
        ]);
        return view('panel::categories.create', compact('form', 'dropdown'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        $data = [];
        $data['name'] = $request->get('name');
        $data['order'] = $request->get('order', 1);
        $data['parent_id'] = $request->get('parent_id');
        $category = Category::create( $data );
		
        return redirect()->route('panel.categories.index')->with('message','Succesfull saved');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        return view('panel::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        $category = Category::findOrFail($id);

        $dropdown = Category::attr(['name' => 'parent_id', 'class'=>  'form-control'])->placeholder(0, '--SELECT--')->orderBy('order', 'ASC')->nested()->selected($category->parent_id)->renderAsDropdown();

        $form = $formBuilder->create('Modules\Panel\Forms\CategoryForm', [
            'method' => 'PUT',
            'url' => route('panel.categories.update', $id),
            'model' => $category
        ]);
        return view('panel::categories.create', compact('form', 'dropdown'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        $category = Category::findOrFail($id);
        $category->fill($request->all());
        $category->save();

        return redirect()->route('panel.categories.index')->with('message','Succesfull saved');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }
        
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('panel.categories.index')->with('message','Succesfull removed');
    }
}

