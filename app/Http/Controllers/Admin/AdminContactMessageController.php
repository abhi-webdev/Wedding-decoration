<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class AdminContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('subject', 'like', "%{$s}%")
                  ->orWhere('message', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.messages.index', compact('messages'));
    }

    public function show($id)
    {
        $message = ContactMessage::findOrFail($id);
        if ($message->status === 'new') {
            $message->status = 'read';
            $message->save();
        }
        return view('admin.messages.show', compact('message'));
    }

    public function updateStatus(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,archived',
            'admin_notes' => 'nullable|string',
        ]);

        $message->status = $validated['status'];
        if ($request->filled('admin_notes')) {
            $message->admin_notes = $validated['admin_notes'];
        }
        $message->save();

        AdminActivityLog::log(
            auth()->id(),
            'status_change',
            'contact_message',
            $message->id,
            "Contact message #{$message->id} from {$message->name} marked as '{$message->status}'"
        );

        return redirect()->back()->with('success', "Message status updated successfully.");
    }

    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $name = $message->name;
        $message->delete();

        AdminActivityLog::log(
            auth()->id(),
            'delete',
            'contact_message',
            $id,
            "Deleted contact message from '{$name}'"
        );

        return redirect()->route('admin.messages.index')->with('success', "Contact message deleted successfully.");
    }
}
