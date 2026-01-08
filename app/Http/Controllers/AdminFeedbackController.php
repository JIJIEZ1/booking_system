<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    // List all feedback
    public function index()
    {
        $feedbacks = Feedback::with('customer', 'booking')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.feedback.index', compact('feedbacks'));
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
