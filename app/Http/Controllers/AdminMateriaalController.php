<?php
  
  namespace App\Http\Controllers;

  use Illuminate\Http\Request;

  class AdminMateriaalController extends Controller
{
    public function index()
    {
        return view('admin-catalogus');
    }

    public function create()
    {
        return view('admin-catalogus-materiaal');
    }

  public function store(Request $request)
{
    dd($request->all());
}
}