<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    // List all feedback
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $query = Feedback::with('customer', 'booking')->orderBy('created_at', 'desc');

        if ($perPage === 'All') {
            $feedbacks = $query->get();
        } else {
            $feedbacks = $query->paginate($perPage)->withQueryString();
        }

        return view('admin.feedback.index', compact('feedbacks', 'perPage'));
    }

    // Show feedback details
    public function show($feedback_id)
    {
        $feedback = Feedback::with('customer', 'booking')->findOrFail($feedback_id);
        return view('admin.feedback.show', compact('feedback'));
    }

    // Show reply form
    public function replyForm($feedback_id)
    {
        $feedback = Feedback::with('customer')->findOrFail($feedback_id);
        return view('admin.feedback.reply', compact('feedback'));
    }

    // Submit reply
    public function replySubmit(Request $request, $feedback_id)
{
    $request->validate([
        'reply_message' => 'required|string',
    ]);

    $feedback = Feedback::findOrFail($feedback_id);
    $feedback->reply = $request->reply_message;
    $feedback->replied_by = auth()->guard('admin')->id();
    $feedback->save();

    return redirect()->route('admin.feedback.index')
                     ->with('success', 'Reply sent successfully.');
}



    // Delete feedback
    public function destroy($feedback_id)
    {
        Feedback::findOrFail($feedback_id)->delete();
        return redirect()->route('admin.feedback.index')->with('success', 'Feedback deleted.');
    }
}
