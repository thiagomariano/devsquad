<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('category.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('category.form');
    }

}
