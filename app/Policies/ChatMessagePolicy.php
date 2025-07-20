<?php

namespace App\Policies;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatMessagePolicy
{
    public function update(User $user, ChatMessage $message)
    {
        return $user->id === $message->user_id;
    }

    public function delete(User $user, ChatMessage $message)
    {
        return $user->id === $message->user_id;
    }
}