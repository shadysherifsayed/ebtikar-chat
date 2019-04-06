<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $touches = ['conversation'];


    protected $guarded = [];

    /**
     * Get the user who send this message
     *
     * @return Object
     */
    public function sender()
    {
        return $this->morphTo();
    }

    /**
     * Get all the users whom the message should get to them
     *
     * @return Collection
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'recipient', 'message_recipient');
    }

    /**
     * Get the conversation that the message has been sent to it
     *
     * @return Conversation
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }


    public function getTimeDifferenceAttribute()
    {

        $messageTime = $this->created_at;

        $now = Carbon::now();

        return $messageTime->diffForHumans($now);
    }


}
