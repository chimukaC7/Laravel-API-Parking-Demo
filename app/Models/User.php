<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Webpatser\Uuid\Uuid;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function parkings(): HasMany
    {
        return $this->hasMany(Parking::class);
    }

    /**
     *  Setup model event hooks
     */
//    public static function boot()
//    {
//        parent::boot();
//        self::creating(function ($model) {
//            $model->uuid = (string) Uuid::generate(4);
//        });
//    }

    /**
     * Get the route key for the model.
     *
     * @return string
     * If you want to use the UUID in URLs instead of the primary key, you can add this to your model (where 'uuid' is the column name to store the UUID)
     */
//    public function getRouteKeyName()
//    {
//        return 'uuid';
//    }
}
