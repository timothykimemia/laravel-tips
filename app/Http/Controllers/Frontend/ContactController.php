<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    public function create()
    {
        return view('frontend.contact.index');
    }

    public function store(StoreContactRequest $request)
    {
        Contact::create($request->validated());

        return redirect()->back()->with([
            'message' => 'Message sent successfully',
            'alert-type' => 'success'
        ]);

    }
}
