<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        $conversation = auth()->user()->createConversation($user);

        return redirect()->route('conversations.show', $conversation);
    }
}
