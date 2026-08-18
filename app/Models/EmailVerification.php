<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class EmailVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'verification_code',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isVerified()
    {
        return !is_null($this->verified_at);
    }

    public function markAsVerified()
    {
        $this->update(['verified_at' => now()]);
    }

    public static function generateCode()
    {
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    public static function createForUser($userId, $email)
    {
        // Delete any existing verification for this user
        self::where('user_id', $userId)->delete();

        return self::create([
            'user_id' => $userId,
            'email' => $email,
            'verification_code' => self::generateCode(),
            'expires_at' => now()->addHours(24),
        ]);
    }
}
