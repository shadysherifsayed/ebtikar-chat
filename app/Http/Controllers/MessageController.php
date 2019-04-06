<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Client;

class MessageController extends Controller
{


    public function store(Request $request, Conversation $conversation)
    {

        $request->validate([
            'message' => 'required'
        ]);

        $message = auth()->user()->sendMessageToConversation($conversation, $request->message);

        $message->load('sender');

        if($request->ajax()) {
            return response()->json(compact('message'));
        }

        return back();
    }

}

