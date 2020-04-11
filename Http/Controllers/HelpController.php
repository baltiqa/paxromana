<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsCategory;
use Validator;

use MetaTag;

class HelpController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }


    public function index(Request $request)
    {
        $newssearch = new News;
        $news = News::where('featured',1)->orderBy('created_at','DESC')->get();
        $newscat = News::where('featured',0)->where('parent_id',0)->orderBy('created_at','DESC')->get();

        $data = [];
        $data['news'] = $news;
        $data['newscat'] = $newscat;


        if($request->get('search')) {
            $newssearch = $newssearch->where('disabled',0)->where('featured',0)->search($request->get('search'))->get();
            $data['newssearches'] = $newssearch;
            return view('page',$data);
        }

        return view('page',$data);
    }

    public function help($id) {

        $news = News::where('featured',1)->orderBy('created_at','DESC')->get();
        $newscat = News::where('featured',0)->where('parent_id',0)->orderBy('created_at','DESC')->get();
        $single_news = News::where('id',$id)->where('disabled',0)->where('featured',0)->first();
        if($single_news == null) {
            abort(404);
        }

        $data = [];
        $data['news'] = $news;
        $data['newscat'] = $newscat;

        $data['single_news'] = $single_news;

        MetaTag::set('title', 'Wiki: '.$single_news->title);

        return view('help',$data);
    }

    public function search(Request $request) {
        $params = $request->all();
        $validator = Validator::make($request->all(), [
            'searchtext' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)
                ->withInput();
        }




        $data = [];
        $news = News::whereLike('title', $request->input('searchtext'))->orWhereLike('body', $request->input('searchtext'))->get();
        $categories = NewsCategory::All();

        $data['categories'] = $categories;
        $data['news'] = $news;
        MetaTag::set('title', "Help");


        return view('page',$data);
    }

    public function voteDown($id) {
        $single_news = News::findOrFail($id);
        $single_news->vote_down = $single_news->vote_down + 1;
        $single_news->save();
        return redirect()->back();
    }

    public function voteUp($id) {
        $single_news = News::findOrFail($id);
        $single_news->vote_up = $single_news->vote_up + 1;
        $single_news->save();

        return redirect()->back();
    }


}
