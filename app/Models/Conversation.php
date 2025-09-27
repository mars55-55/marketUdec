<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user1_id',
        'user2_id',
        'listing_id',
        'last_message_at',
        'blocked_by',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'blocked_by' => 'json',
    ];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    // Métodos auxiliares
    public function getOtherUser($currentUserId)
    {
        return $this->user1_id == $currentUserId ? $this->user2 : $this->user1;
    }

    public function isBlockedBy($userId)
    {
        $blockedBy = $this->blocked_by ?? [];
        return in_array($userId, $blockedBy);
    }

    public function blockUser($userId)
    {
        $blockedBy = $this->blocked_by ?? [];
        if (!in_array($userId, $blockedBy)) {
            $blockedBy[] = $userId;
            $this->blocked_by = $blockedBy;
            $this->save();
        }
    }

    public function unblockUser($userId)
    {
        $blockedBy = $this->blocked_by ?? [];
        $this->blocked_by = array_values(array_diff($blockedBy, [$userId]));
        $this->save();
    }
}
