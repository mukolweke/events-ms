<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'phone',
        'registered_at',
    ];

    protected $dates = [
        'registered_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope('organization', function (Builder $builder) {
            $organization = app('currentOrganization');
            if ($organization) {
                $builder->whereHas('event', function ($query) use ($organization) {
                    $query->where('organization_id', $organization->id);
                });
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function setRegisteredAtAttribute($value)
    {
        $this->attributes['registered_at'] = $value ?: now();
    }

    public static function isEmailUniqueForEvent(string $email, int $eventId): bool
    {
        return !static::where('email', $email)
            ->where('event_id', $eventId)
            ->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attendee) {
            if (!$attendee->registered_at) {
                $attendee->registered_at = now();
            }
        });

        static::saving(function ($attendee) {
            if (!$attendee->isEmailUniqueForEvent($attendee->email, $attendee->event_id)) {
                throw new \Exception('Email already registered for this event.');
            }
        });
    }
}
