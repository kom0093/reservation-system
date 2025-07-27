<?php

namespace App\Models;

use App\Models\Traits\ModelTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, ModelTrait;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // <editor-fold desc="Region: RELATIONS">
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'user_id', 'id');
    }
    // </editor-fold desc="Region: RELATIONS">

    // <editor-fold desc="Region: Getters">
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFullname(): string
    {
        return $this->getFirstName().' '.$this->getLastName();
    }
    // </editor-fold desc="Region: Getters">

    // <editor-fold desc="Region: JWT">
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'user_id' => $this->getId(),
            'email' => $this->getEmail(),
        ];
    }

    // </editor-fold desc="Region: JWT">

    public function getToArray(): array
    {
        return [
            'id' => $this->getId(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'full_name' => $this->getFullname(),
            'email' => $this->getEmail(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
