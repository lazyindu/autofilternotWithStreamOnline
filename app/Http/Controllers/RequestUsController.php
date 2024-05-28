<?php

namespace App\Http\Controllers;

use App\Models\RequestUs;
use Illuminate\Http\Request;

class RequestUsController extends Controller
{
    public function request(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'requested_for' => 'required',
            'description' => 'required'
        ]);

        $sendrequest = RequestUs::create($data);
        return redirect()->back()->with('alert' , 'Request sent to admin successfully ✔');

    }
}
