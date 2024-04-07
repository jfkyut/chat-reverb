<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        return Message::with('user')->get();
    }

    public function store(Request $request)
    {
        $message = Auth::user()->messages()->create([
            'text' => $request->input('message'),
        ]);

        MessageCreated::dispatch($message->with('user')->where('id', $message->id)->first());

        return response()->noContent();
    }
}
