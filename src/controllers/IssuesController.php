<?php

namespace Ecd\Feedbacktool\Controllers;

use Auth;
use Illuminate\Http\Request;
use Ecd\Feedbacktool\Models\Gitnote;
use Ecd\Feedbacktool\Models\Issue;
use App\Http\Controllers\Controller;

class IssuesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        include __DIR__."/../config.php";

        $issues = Issue::where('user_id', Auth::user()->id)->get();

        // Uncomment this if you just want to return user issue data
        // return $activeIssues;

        // Return data to a view
        return view('issue', compact('issues'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'label' => 'required|string'    
        ]);

        Issue::sendToGitlab($request);

        return redirect('/home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getNotes($id)
    {
        $issueNotes = Gitnote::where('issue_id', $id)->get();

        // Uncomment to return data on its own
        // return $issueNotes;

        // Return notes data to view
        return view('notes', compact('issueNotes'));
    }
}
