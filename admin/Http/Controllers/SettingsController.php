<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Setting;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, FormBuilder $formBuilder)
    {
        $settings = Setting::all();

        if(auth()->user()->username != "Augustus"){
            abort(404);
        }

        $form = $formBuilder->create('Modules\Panel\Forms\GeneralSettingsForm', [
            'method' => 'POST',
            'url' => route('panel.settings.store'),
        ], $settings);
        return view('panel::settings.index', compact('form'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }
        
        return view('panel::create');
    }


    public function remove(Request $request) {
      
    }

    public function store(Request $request)
    {
        if(auth()->user()->username != "Augustus"){
            abort(404);
        }


        Setting::set('marketplace_transaction_fee', $request->input('marketplace_transaction_fee'));
        Setting::set('marketplace_withdraw_fee', $request->input('marketplace_withdraw_fee'));
     
        Setting::save();

        return redirect()->route('panel.settings.index')->with('message','Succesfull saved');
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


}

