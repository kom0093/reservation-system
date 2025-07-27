<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use App\Models\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory, ModelTrait;

    // <editor-fold desc="Region: STATE DEFINITION">
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected function casts(): array
    {
        return [
            'datetime' => 'datetime',
        ];
    }
    // </editor-fold desc="Region: STATE DEFINITION">


    // <editor-fold desc="Region: RELATIONS">
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reservationTable(): BelongsTo
    {
        return $this->belongsTo(Table::class, 'table_id', 'id');
    }
    // </editor-fold desc="Region: RELATIONS">

    // <editor-fold desc="Region: GETTERS">
    public function getDatetime(): Carbon
    {
        return $this->datetime;
    }
    // </editor-fold desc="Region: GETTERS">

    // <editor-fold desc="Region: COMPUTED GETTERS">
    // </editor-fold desc="Region: COMPUTED GETTERS">

    // <editor-fold desc="Region: ARRAY GETTERS">
    public function getToArray(): array
    {
        return [
            'id' => $this->getId(),
            'table_id' => $this->reservationTable->getId(),
            'user_id' => $this->user->getId(),
            'table_capacity' => $this->reservationTable->getCapacity(),
            'datetime' => $this->getDatetime()->format('d.m.Y H:i'),
            'created_at' => $this->getCreatedAt()->format('d.m.Y H:i'),
        ];
    }
    // </editor-fold desc="Region: ARRAY GETTERS">
}
