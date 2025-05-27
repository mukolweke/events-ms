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

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'venue',
        'event_date',
        'price',
        'max_attendees',
        'is_active',
    ];

    protected $dates = [
        'event_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('organization', function (Builder $builder) {
            $organization = app('currentOrganization');
            if ($organization) {
                $builder->where('organization_id', $organization->id);
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }
}
