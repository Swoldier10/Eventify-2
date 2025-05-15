<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableArrangement extends Model
{
    protected $fillable = [

        'invitation_id',
        'max_seats_per_table'
    ];

    public function invitation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}
