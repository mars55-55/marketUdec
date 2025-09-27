<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id',
        'reviewed_id',
        'listing_id',
        'rating',
        'comment',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewed()
    {
        return $this->belongsTo(User::class, 'reviewed_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
