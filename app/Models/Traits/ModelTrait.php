<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait ModelTrait {
    public function getId(): int
    {
        $primaryKey = $this->primaryKey;
        return $this->$primaryKey;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    public function getDeletedAt(): ?Carbon
    {
        return method_exists($this, 'trashed') ? $this->deleted_at : null;
    }
}
