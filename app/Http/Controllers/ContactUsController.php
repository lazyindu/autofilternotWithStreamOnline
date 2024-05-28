<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function message(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'website' => 'required',
            'message' => 'required'
        ]);

        $message = ContactUs::create($data);
        return redirect()->back()->with('alert' , 'Message submitted successfully ✔');

    }
}
