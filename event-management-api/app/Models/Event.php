<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'venue',
        'event_date',
        'start_date',
        'end_date',
        'price',
        'max_attendees',
        'is_active',
        'status',
        'is_public',
    ];

    protected $dates = [
        'event_date',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'max_attendees' => 'integer',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>', now());
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', now());
    }

    public function scopeForCurrentOrganization($query)
    {
        $organization = app('currentOrganization');
        return $query->where('organization_id', $organization->id);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->attendees()->count() >= $this->max_attendees;
    }

    public function getRemainingCapacityAttribute(): int
    {
        return max(0, $this->max_attendees - $this->attendees()->count());
    }

    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        if ($this->event_date < now()) {
            return 'completed';
        }

        if ($this->isFull) {
            return 'full';
        }

        return 'active';
    }

    public function setEventDateAttribute($value)
    {
        $this->attributes['event_date'] = Carbon::parse($value);
    }

    public function canRegister(): bool
    {
        return $this->is_active && !$this->isFull && $this->event_date > now();
    }

    public function registerAttendee(array $data): Attendee
    {
        if (!$this->canRegister()) {
            throw new \Exception('Cannot register for this event.');
        }

        return $this->attendees()->create($data);
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
