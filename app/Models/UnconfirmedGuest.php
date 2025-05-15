<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnconfirmedGuest extends Model
{
    protected $table = 'unconfirmed_guests';

    // The attributes that are mass assignable.
    protected $fillable = [
        'nr_of_people',
        'name',
        'phone_number',
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
}
