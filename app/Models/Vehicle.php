<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'plate_number', 'description'];

    protected static function booted()
    {
        //Now, we need to filter out the data while getting the Vehicles.
        // For that, we will set up a Global Scope in Eloquent. It will help us to avoid the ->where() statement every we would need it
        static::addGlobalScope('user', function (Builder $builder) {
            $builder->where('user_id', auth()->id());
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parkings(): HasMany
    {
        return $this->hasMany(Parking::class);
    }

    public function activeParkings()
    {
        return $this->parkings()->active();
    }

    public function hasActiveParkings(): bool
    {
        return $this->activeParkings()->exists();
    }
}
