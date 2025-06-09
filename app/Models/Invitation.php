<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'name',
        'email',
        'secondary_email',
        'bride_first_name',
        'bride_last_name',
        'groom_first_name',
        'groom_last_name',
        'individual_photo',
        'bride_photo',
        'bride_text',
        'groom_photo',
        'groom_text',
        'couple_photo',
        'couple_text',
        'godparents',
        'parents',
        'civil_wedding_address',
        'civil_wedding_city',
        'civil_wedding_country',
        'civil_wedding_datepicker',
        'religious_wedding_address',
        'religious_wedding_city',
        'religious_wedding_country',
        'religious_wedding_datepicker',
        'party_address',
        'party_city',
        'party_country',
        'party_datepicker',
        'background_photo_first_page',
        'invitation_subtitle',
        'title_color',
        'subtitle_color',
        'countdown_image',
        'countdown_text',
        'countdown',
        'couple_section_image',
        'description_title',
        'description_subtitle',
        'description_section_text',
        'additional_question',
        'additional_text',
        'confirmation_deadline',
        'whatsapp_thumbnail',
        'text_displayed_when_sharing',
        'need_accommodation',
        'need_vegetarian_menu',
        'possibility_to_select_nr_kids',
        'plan_id',
        'invitation_link',
        'confirmation_possibility',
        'limit_confirmation_once',
        'invitation_password',
        'event_type_id',
        'user_id',

//        for baptism
        'child_name',
        'twin_name',
        'kids_text',
        'child_photo',
        'child_section_image',
        'celebrants_photo_type',
    ];

    protected $casts = [
        'celebrants_photo_type' => 'string',
    ];

    public static array $generalInfoFields = [
        'name',
        'email',
        'secondary_email'
    ];

    public static array $celebrantsDetailsFields = [
        'bride_first_name',
        'bride_last_name',
        'groom_first_name',
        'groom_last_name',
        'bride_photo',
        'groom_photo',
        'celebrants_photo_type',
        'bride_text',
        'groom_text',
        'godparents',
        'parents'
    ];

    public static array $locationFields = [
        'civil_wedding_address',
        'civil_wedding_city',
        'civil_wedding_country',
        'civil_wedding_datepicker',
        'religious_wedding_address',
        'religious_wedding_city',
        'religious_wedding_country',
        'religious_wedding_datepicker',
        'party_address',
        'party_city',
        'party_country',
        'party_datepicker'
    ];

    public static array $advancedCustomizationFields = [
        'background_photo_first_page',
        'invitation_subtitle',
        'title_color',
        'subtitle_color',
        'countdown_image',
        'countdown_text',
        'countdown',
        'couple_section_image',
        'description_title',
        'description_subtitle',
        'description_section_text',
        'need_accommodation',
        'need_vegetarian_menu',
        'possibility_to_select_nr_kids',
        'additional_question',
        'additional_text',
        'confirmation_deadline',
        'whatsapp_thumbnail',
        'text_displayed_when_sharing'
    ];

    public static array $pricingFields = [
        'plan_id'
    ];

    public static array $guestsSettingsFields = [
        'invitation_link',
        'confirmation_possibility',
        'limit_confirmation_once',
        'password'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function eventType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(EventType::class);
    }

    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    public function tableArrangement(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(TableArrangement::class);
    }

    public function celebrantsImageDisplayType(): Attribute
    {
        $displayType = 'no_photo';

        if ($this->bride_photo) {
            $displayType = 'individual_photo';
        }

        if ($this->couple_photo) {
            $displayType = 'common_photo';
        }

        return Attribute::make(
            get: fn() => $displayType
        );
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
