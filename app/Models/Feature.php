<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name'
    ];

    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_features', 'feature_id', 'plan_id');
    }
}
