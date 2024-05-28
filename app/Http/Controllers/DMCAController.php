<?php

namespace App\Http\Controllers;

use App\Models\DMCA;
use Illuminate\Http\Request;

class DMCAController extends Controller
{
    public function report(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ]);

        $report = DMCA::create($data);

        return redirect()->back()->with('alert' , 'Report submitted successfully ✔');
    
    }
}
