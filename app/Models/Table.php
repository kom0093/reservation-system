<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory, ModelTrait;

    // <editor-fold desc="Region: STATE DEFINITION">
    protected $guarded = ['id', 'created_at', 'updated_at'];
    // </editor-fold desc="Region: STATE DEFINITION">


    // <editor-fold desc="Region: RELATIONS">
    public function reservation(): HasMany
    {
        return $this->hasMany(Reservation::class, 'table_id', 'id');
    }
    // </editor-fold desc="Region: RELATIONS">

    // <editor-fold desc="Region: ARRAY GETTERS">
    public function getCapacity(): int {
        return $this->capacity;
    }
    // </editor-fold desc="Region: ARRAY GETTERS">

}
