<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelpRequest;
use Carbon\Carbon;

class HelpRequestController extends Controller
{
    public function index(){
        $requests = HelpRequest::all();
        return view('help-requests.index', compact('requests'));
    }

    public function create(){
        return view('help-requests.create');
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'title' => 'required',
                'description' => 'required'
            ]);

            HelpRequest::create($validated);
            return redirect()->route('login')->with('status', 'Jouw aanvraag werd gestuurd!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('status', 'Jouw aanvraag werd niet doorgestuurd, probeer het opnieuw later.');
        }
    }
}
