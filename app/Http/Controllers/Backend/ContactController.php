<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Traits\FilterTrait;

class ContactController extends Controller
{
    use FilterTrait;

    public function index()
    {
        $this->authorize('view-contact');

        $query = Contact::query();
        $messages = $this->filter($query);

        return view('backend.contact_us.index', compact('messages'));
    }

    public function show(Contact $contact)
    {
        $this->authorize('view-contact');

        if ($contact->status == 0) {
            $contact->status = 1;
            $contact->save();
        }
        return view('backend.contact_us.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $this->authorize('delete-contact');

        $contact->delete();

        return redirect()->route('admin.contacts.index')->with([
            'message' => 'Message deleted successfully',
            'alert-type' => 'success',
        ]);
    }
}
