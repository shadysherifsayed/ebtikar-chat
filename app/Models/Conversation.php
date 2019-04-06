<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{


    protected $guarded = [];

    /**
     * Get all of the users that are participate in this conversation
     *
     * @return collection
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'participant', 'conversation_participant');
    }

    /**
     * Return all messages in this conversation in descending order
     *
     * @return collection
     */
    public function messages()
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function participants()
    {
        return $this->users;
    }

    public function name()
    {

        if ($this->group) return $this->name;

        foreach ($this->participants() as $participant) {
            if (!auth()->user()->is($participant)) return $participant->name;
        }
    }

    public function markAsRead()
    {
        auth()->user()->markConversationAsRead($this);
    }

    public function markAsUnread()
    {
        auth()->user()->markConversationAsUnread($this);
    }


    public function scopeJoined($query)
    {
        return $query->where('conversation_participant.is_left', false);
    }

}
