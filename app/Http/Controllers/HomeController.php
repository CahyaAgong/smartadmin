<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        return view('home');
    }

    public function agenda(){
        return view('pages/agenda/index');
    }

    public function pengumuman(){
        return view('pages/pengumuman/index');
    }

    public function runningtext(){
        return view('pages/runningtext/index');
    }

    public function account(){
        return view('pages/account/index');
    }
}
