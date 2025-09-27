<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'career',
        'campus',
        'bio',
        'phone',
        'profile_photo',
        'privacy_settings',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'privacy_settings' => 'json',
            'rating' => 'decimal:2',
            'is_moderator' => 'boolean',
            'is_blocked' => 'boolean',
        ];
    }

    /**
     * Relaciones
     */
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewed_id');
    }

    public function givenReviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'user1_id')
                   ->orWhere('user2_id', $this->id);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    /**
     * Métodos auxiliares
     */
    public function getAvatarAttribute()
    {
        return $this->profile_photo 
            ? asset('storage/' . $this->profile_photo)
            : "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=random";
    }

    public function updateRating()
    {
        $reviews = $this->reviews()->where('is_public', true);
        $this->rating_count = $reviews->count();
        $this->rating = $this->rating_count > 0 ? $reviews->avg('rating') : 0;
        $this->save();
    }
}
