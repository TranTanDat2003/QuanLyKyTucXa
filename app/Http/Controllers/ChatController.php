<?php

namespace App\Http\Controllers;

use App\Events\GreetingSent;
use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        return view('student.chat.index');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        broadcast(new MessageSent($request->user(), $request->message));

        return response()->json(['success' => true, 'message' => $request->message]);
    }

    public function greetReceived(Request $request, User $receiver)
    {
        broadcast(new GreetingSent($receiver, "{$request->user()->username} đã chào bạn"));

        broadcast(new GreetingSent($request->user(), "Bạn đã chào {$receiver->username}"));

        return response()->json(['success' => true, 'message' => "Lời chào đã được gửi đến {$receiver->username}"]);
    }
}
