<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @method MorphMany notifications()
 * @property-read \Illuminate\Database\Eloquent\Collection|DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|DatabaseNotification[] $unreadNotifications
 * @property-read int $notifications_count
 * @property-read int $unread_notifications_count
 */
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
        'is_admin',
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

    /**
     * Accessor para obtener el conteo de reseñas
     */
    public function getRatingCountAttribute($value)
    {
        // Usar el valor de la base de datos si existe, sino calcular
        if (isset($this->attributes['rating_count'])) {
            return $this->attributes['rating_count'];
        }
        return $this->reviews()->count();
    }

    /**
     * Accessor para obtener el rating formateado
     */
    public function getFormattedRatingAttribute()
    {
        $count = $this->getRatingCountAttribute(null);
        if ($count === 0) {
            return null; // No mostrar rating si no hay reseñas
        }
        
        $rating = $this->attributes['rating'] ?? 0;
        return number_format($rating, 1);
    }

    /**
     * Verificar si el usuario tiene reseñas
     */
    public function hasReviews()
    {
        return $this->getRatingCountAttribute(null) > 0;
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

    /**
     * Verificar si el usuario permite mensajes
     */
    public function allowsMessages()
    {
        $privacy = $this->privacy_settings ?? [];
        return $privacy['allow_messages'] ?? true;
    }

    /**
     * Verificar si un campo específico es visible
     */
    public function showsField($field)
    {
        $privacy = $this->privacy_settings ?? [];
        
        $defaults = [
            'show_email' => false,
            'show_phone' => false,
            'show_campus' => true,
            'show_career' => true,
            'show_listings_count' => true,
        ];

        return $privacy[$field] ?? $defaults[$field] ?? false;
    }
}
