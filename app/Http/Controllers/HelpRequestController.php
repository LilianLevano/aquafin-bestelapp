<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelpRequest;
use Carbon\Carbon;

class HelpRequestController extends Controller
{
    public function index(string $is_completed){

        if($is_completed == 'completed'){
            $requests = HelpRequest::where('is_completed', 1)->orderBy('created_at', 'desc')->get();
        }else if ($is_completed == 'open'){
            $requests = HelpRequest::where('is_completed', 0)->orderBy('created_at', 'desc')->get();
        }else {
            $requests = HelpRequest::all();
        }

        return view('help-requests.index', compact('requests'));
    }

    public function create(){
        return view('help-requests.create');
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => ['required', 'min:2'],
                'last_name' => ['required', 'min:2'],
                'email' => ['required','email',],
                'title' => ['required', 'max:50'],
                'description' => ['required', 'max:400'],
            ]);

            HelpRequest::create($validated);
            return redirect()->route('login')->with('status', 'Jouw aanvraag werd gestuurd!');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('status', 'Jouw aanvraag werd niet doorgestuurd, probeer het opnieuw later.');
        }
    }
    public function edit($id){
        $request = HelpRequest::findOrFail($id);
        return view('help-requests.partials.answer', compact('request'));
    }

    public function update(Request $request, $id){
        try{
            $validated = $request->validate([
                'answer' => ['required'],
            ]);

            $helpRequest = HelpRequest::findOrFail($id);

            $helpRequest->is_completed = 1;
            $helpRequest->update($validated);
            return redirect()->route('admin.help-requests.index', "all")->with('success', 'Jouw aanvraag werd gestuurd!');
        }catch (\Exception $e){
            return redirect()->route('admin.help-requests.index', "all")->with('error', $e->getMessage());
        }
    }

    public function show($id){
        $request = HelpRequest::findOrFail($id);
        return view('help-requests.show', compact('request'));
    }
}
