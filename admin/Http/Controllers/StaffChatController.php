<?php

namespace App\Http\Controllers\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Kris\LaravelFormBuilder\FormBuilder;
use App\Http\Controllers\NotificationController;


class StaffChatController extends Controller
{

  

    public function index(Request $request)
    {


        return view('panel::staffchat.index');
    }

  
}
