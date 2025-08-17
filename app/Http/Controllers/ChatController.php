<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function listConversations()
    {
        $items = Conversation::where('user_id', Auth::id())
            ->latest('last_message_at')
            ->paginate(20);
        return response()->json($items);
    }

    public function getMessages($conversationId)
    {
        $conv = Conversation::where('user_id', Auth::id())->findOrFail($conversationId);
        $messages = $conv->messages()->orderBy('created_at')->get();
        return response()->json(['conversation' => $conv, 'messages' => $messages]);
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $conv = Conversation::where('user_id', Auth::id())->findOrFail($conversationId);
        $data = $request->validate(['body' => ['required', 'string', 'max:5000']]);
        $message = $conv->messages()->create([
            'sender_id' => Auth::id(),
            'sender_type' => 'user',
            'body' => $data['body'],
        ]);
        $conv->update(['last_message_at' => now()]);
        return response()->json(['status' => 'sent', 'message' => $message]);
    }
}


