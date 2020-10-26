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

    public function slider(){
        return view('pages/slider/index');
    }

    public function foto(){
        return view('pages/foto/index');
    }

    public function video(){
        return view('pages/video/index');
    }

    public function text(){
        return view('pages/text/index');
    }

    public function logo(){
        return view('pages/logo/index');
    }

    public function member(){
        return view('pages/member/index');
    }
}
