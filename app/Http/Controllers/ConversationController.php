<?php

namespace App\Http\Controllers;

use App\Models\Conversation;

class ConversationController extends Controller
{

    public function index()
    {

        $conversations = auth()->user()->conversations()->joined()->get();

        $conversation = null;

        if($conversations->count()) {
            $conversation = $conversations->first()->load('messages.sender');
        }

        return view('conversations.index', compact('conversations', 'conversation'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function show(Conversation $conversation)
    {

        $conversations = auth()->user()->conversations()->joined()->get();

        $conversation->load('messages.sender');

        $conversation->markAsRead();

        return view('conversations.index', compact('conversation', 'conversations'));
    }

    /**
     * Leave a conversation
     *
     * @param  \App\Models\Conversation  $conversation
     * @return \Illuminate\Http\Response
     */
    public function leave(Conversation $conversation)
    {
        auth()->user()->leaveConversation($conversation);

        return redirect()->route('conversations.index');
    }

}
