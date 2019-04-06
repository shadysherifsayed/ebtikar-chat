<?php

namespace App\Traits;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Collection;

trait ConversationParticipant
{


    /**
     * Get all of the conversations for the participant.
     *
     * @return Collection
     */
    public function conversations()
    {
        return $this->morphToMany(Conversation::class, 'participant', 'conversation_participant')
            ->latest('updated_at');
    }

    /**
     * Get all of the messages that the participant has sent.
     *
     * @return Collection
     */

    public function sentMessages()
    {
        return $this->morphMany(Message::class, 'sender');
    }


    /**
     * Get all of the messages that the participant should receive.
     *
     * @return Collection
     */
    public function messages()
    {
        return $this->morphToMany(Message::class, 'recipient', 'message_recipient');
    }


    /**
     * Create a conversation between participants
     *
     * @param Collection or Object $participants
     * @param [string] $name
     * @param [string] $image
     * @param boolean $group
     * @return Conversation
     */
    public function createConversation($participants, $name = null, $image = null, $group = false)
    {

        /**
         * If the participants are not a collection
         * Make it as a collection
         */
        if (!$participants instanceof Collection) {
            $collect = collect();
            $collect->push($participants);
            $participants = $collect;
        }

        /**
         * If the conversation isn't a group
         * and there is a private conversation already exists between
         * loggedUser and participant return this conversation
         */
        if (!$group) {
            $conversation = $this->getPrivateConversation($participants->first());
            if ($conversation) {
                $this->conversations()->updateExistingPivot($conversation->id, [
                    'is_left' => false
                ]);
                return $conversation;
            }
        }

        $conversation = Conversation::create(compact('name', 'image', 'group'));

        $participants->push($this);

        $participants->each(function ($participant) use ($conversation) {
            $participant->joinConversation($conversation);
        });

        return $conversation;
    }


    /**
     * return the private conversation between the logged user and any user
     *
     * @param [mixed] $participant
     * @return Conversation $conversation || null
     */
    public function getPrivateConversation($participant)
    {

        foreach ($this->conversations as $conversation) {
            foreach ($conversation->participants() as $conversationParticipant) {
                if ($conversationParticipant->is($participant) && !$conversation->group) return $conversation;
            }
        }

        return null;
    }


    /**
     * Send message to conversation
     *
     * @param Conversation $conversation
     * @param [string] $message
     * @return Message
     */
    public function sendMessageToConversation(Conversation $conversation, $message)
    {
        $message = $this->sentMessages()->create([
            'body' => $message,
            'conversation_id' => $conversation->id
        ]);

        $conversation->participants()->each(function ($participant) use ($message) {
            $sender = auth()->user()->is($participant);
            $participant->messages()->attach($message, [
                'is_sender' => $sender,
                'is_read' => $sender
            ]);
        });

        return $message;
    }

    /**
     * Send message to participant
     *
     * @param [Mixed] $participant
     * @param [String] $message
     * @return Message
     */
    public function sendMessageToParticipant($participant, $message)
    {

        $conversation = $this->getPrivateConversation($participant) ?? $this->createConversation($participant);

        return $this->sendMessageToConversation($conversation, $message);
    }

    /**
     * Join a conversation
     *
     * @param Conversation $conversation
     * @return void
     */
    public function joinConversation(Conversation $conversation)
    {
        $this->conversations()->attach($conversation);
    }

    /**
     * Leave a conversation
     *
     * @param Conversation $conversation
     * @return void
     */
    public function leaveConversation(Conversation $conversation)
    {
        $this->conversations()->updateExistingPivot($conversation->id, [
            'is_left' => true
        ]);
    }

    /**
     * Mark conversation as read
     *
     * @param [Conversation] $conversation
     * @param boolean $is_read
     * @return void
     */
    public function markConversationAsRead(Conversation $conversation, $is_read = true)
    {
        $this->conversations()->updateExistingPivot($conversation->id, [
            'is_read' => $is_read
        ]);

        $messages = $conversation->messages;

        $messages->each(function ($message) use ($is_read) {
            $this->messages()->updateExistingPivot($message->id, [
                'is_read' => $is_read
            ]);
        });
    }

    /**
     * Mark conversation as unread
     *
     * @param [Conversation] $conversation
     * @return void
     */
    public function markConversationAsUnread(Conversation $conversation)
    {
        $this->markConversationAsRead($conversation, false);
    }
}
