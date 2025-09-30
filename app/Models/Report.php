<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'listing_id',
        'reported_user_id',
        'type',
        'reason',
        'description',
        'status',
        'moderator_id',
        'moderator_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function resolve($moderatorId, $notes = null)
    {
        $this->update([
            'status' => 'resolved',
            'moderator_id' => $moderatorId,
            'moderator_notes' => $notes,
            'resolved_at' => now(),
        ]);
    }
}
