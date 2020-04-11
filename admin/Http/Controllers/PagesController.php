<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {

        $pages = new News();
        $data = [];
        if($request->get('q')) {
            $pages = $pages->search($request->get('q'));
        } else {
			$pages = $pages->orderBy('created_at', 'desc');;
        }

        $data['pages'] = $pages->paginate(20);

        return view('panel::wiki.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create('Modules\Panel\Forms\PageForm', [
            'method' => 'POST',
            'url' => route('panel.wiki.store'),
        ]);
        return view('panel::wiki.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $page = new News();
        $page->fill($request->all());
        $page->save();

        return redirect()->route('panel.wiki.index')->with('message','Succesfull removed');;
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('panel::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $page = News::findOrFail($id);


        $form = $formBuilder->create('Modules\Panel\Forms\PageForm', [
            'method' => 'PUT',
            'url' => route('panel.wiki.update', $id),
            'model' => $page
        ]);
        return view('panel::wiki.create', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $page = News::findOrFail($id);
        $page->fill($request->all());
        $page->save();

        return redirect()->back()->with('message','Succesfull saved');;
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        return redirect()->route('panel.wiki.index')->with('message','Succesfull removed');
    }
}
