<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Models\Comment;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, FormBuilder $formBuilder)
    {

        if(auth()->user()->username != "Augustus"){
            abort(404);
        }


        $comments = new Comment();

        if($request->get('q')) {
            $comments = $comments->search($request->get('q'));
        } else {
            $comments = $comments->orderBy('created_at', 'ASC');
        }

        $data['comments'] = $comments->paginate(25);

        return view('panel::reviews.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(FormBuilder $formBuilder)
    {

    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
     
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
      
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

		
		$comment = Comment::findOrFail($id);
        $form = $formBuilder->create('Modules\Panel\Forms\ReviewForm', [
            'method' => 'PUT',
            'url' => route('panel.reviews.update', $id),
            'model' => $comment
        ]);

        return view('panel::reviews.create', compact('form', 'comment'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        
		#dd($params['form_input_meta']);
        #$params['form_input_meta'] = $params['form_input_meta'];

        $comment = Comment::findOrFail($id);
    
        $comment->comment = $request->input('comment');
        $comment->save();

        
        return redirect(route('panel.reviews.index'))->with('message','Succesfull saved');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
	
    }
}

