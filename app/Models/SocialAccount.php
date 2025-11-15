<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'provider_token',
        'provider_refresh_token',
        'provider_email',
        'provider_email_verified',
        'avatar',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'provider_email_verified' => 'boolean',
    ];

    /**
     * Get the user that owns the social account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if provider email matches user's primary email.
     */
    public function emailMatchesPrimary(): bool
    {
        return $this->provider_email === $this->user->email;
    }

    /**
     * Check if provider email matches user's alternate email.
     */
    public function emailMatchesAlternate(): bool
    {
        return $this->provider_email === $this->user->alternate_email;
    }

    /**
     * Check if provider email is already used by the user.
     */
    public function emailIsUsedByUser(): bool
    {
        return $this->emailMatchesPrimary() || $this->emailMatchesAlternate();
    }
}

