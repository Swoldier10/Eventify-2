<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'nr_of_people',
        'name',
        'partner_name',
        'phone_number',
        'nr_of_kids',
        'need_accomodation',
        'vegetarian_menu',
        'note',
        'additional_question_answer',
        'user_id',
        'invitation_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invitation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    public function tables()
    {
        return $this->belongsToMany(Table::class, 'guest_table')->withTimestamps();
    }
}
