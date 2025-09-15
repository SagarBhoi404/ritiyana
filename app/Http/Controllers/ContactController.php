<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first-name' => 'required|string|max:255',
            'last-name'  => 'required|string|max:255',
            'email'      => 'required|email',
            'phone'      => 'nullable|string|max:20',
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string',
        ]);

        Contact::create([
            'first_name' => $validated['first-name'],
            'last_name'  => $validated['last-name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'] ?? null,
            'subject'    => $validated['subject'],
            'message'    => $validated['message'],
        ]);

return redirect()->back()->with('success', 'Thank you! Your message has been received. Our team will review it and get back to you shortly.');
    }
}

?>
