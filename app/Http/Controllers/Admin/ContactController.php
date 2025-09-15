<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // List all contacts
   public function index()
{
    // Get paginated contacts for the table
    $contacts = Contact::latest()->paginate(10);

    // Get counts for the dashboard cards
    $totalContacts = Contact::count();
    $pendingContacts = Contact::where('status', 'pending')->count();
    $resolvedContacts = Contact::where('status', 'resolved')->count();

    // Pass everything to the view
    return view('admin.contacts.index', compact(
        'contacts', 'totalContacts', 'pendingContacts', 'resolvedContacts'
    ));
}


    // Show single contact
    public function show(Contact $contact)
    {
        return view('admin.contacts.show', compact('contact'));
    }

    // Show edit form
    public function edit(Contact $contact)
    {
        return view('admin.contacts.edit', compact('contact'));
    }

    // Update contact (e.g., status)
    
    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved',
        ]);

        $contact->status = $request->status;
        $contact->save();

        return redirect()->route('admin.contacts.index')
                         ->with('success', 'Contact status updated successfully.');
    }

    // Delete contact
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Contact deleted successfully!');
    }
}
