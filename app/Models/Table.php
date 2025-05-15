<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'invitation_id',
        'table_number'
    ];

    public function guests()
    {
        return $this->belongsToMany(Guest::class, 'guest_table', 'table_id', 'guest_id')
            ->withTimestamps()
            ->withPivot('seats_reserved');
    }

    public function invitation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    public function tableArrangement(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TableArrangement::class);
    }
}
