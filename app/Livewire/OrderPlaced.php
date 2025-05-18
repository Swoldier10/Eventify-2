<?php

namespace App\Livewire;

use App\Models\Invitation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class OrderPlaced extends Component
{
    public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return view('livewire.order-placed');
    }

    public function saveData()
    {
        $cachedData = Cache::get('eventify-cached-data');

        $data = [
            // simple scalar fields, with null coalesce
            'name' => $cachedData['name'] ?? null,
            'email' => $cachedData['email'] ?? null,
            'secondary_email' => $cachedData['secondary_email'] ?? null,
            'bride_first_name' => $cachedData['bride_first_name'] ?? null,
            'bride_last_name' => $cachedData['bride_last_name'] ?? null,
            'groom_first_name' => $cachedData['groom_first_name'] ?? null,
            'groom_last_name' => $cachedData['groom_last_name'] ?? null,

            // display_type comes from a single value
            'individual_photo' => $cachedData['display_type'] ?? null,

            // these are arrays of stored paths, so we head() them only if non-empty
            'common_photo' => is_array($cachedData['common_photo'] ?? null) ? head($cachedData['common_photo']) : $cachedData['common_photo'] ?? null,

            // boolean flags (ensure you cast or normalize as needed)
            'no_photo' => isset($cachedData['no_photo']) && (bool)$cachedData['no_photo'],

            // more “head of array” file fields
            'bride_photo' => is_array($cachedData['bride_photo'] ?? null) ? head($cachedData['bride_photo']) : $cachedData['bride_photo'] ?? null,
            'bride_text' => $cachedData['bride_text'] ?? null,
            'groom_photo' => is_array($cachedData['groom_photo'] ?? null) ? head($cachedData['groom_photo']) : $cachedData['groom_photo'] ?? null,
            'groom_text' => $cachedData['groom_text'] ?? null,
            'couple_photo' => is_array($cachedData['couple_photo'] ?? null) ? head($cachedData['couple_photo']) : $cachedData['couple_photo'] ?? null,
            'couple_text' => $cachedData['couple_text'] ?? null,

            // simple strings
            'godparents' => $cachedData['godparents'] ?? null,
            'parents' => $cachedData['parent'] ?? null,

            // wedding addresses + dates
            'civil_wedding_address' => $cachedData['civil_wedding_address'] ?? null,
            'civil_wedding_city' => $cachedData['civil_wedding_city'] ?? null,
            'civil_wedding_country' => $cachedData['civil_wedding_country'] ?? null,
            'civil_wedding_datepicker' => Carbon::parse($cachedData['civil_wedding_date_time'])->format('Y-m-d H:i:s') ?? null,

            'religious_wedding_address' => $cachedData['religious_wedding_address'] ?? null,
            'religious_wedding_city' => $cachedData['religious_wedding_city'] ?? null,
            'religious_wedding_country' => $cachedData['religious_wedding_country'] ?? null,
            'religious_wedding_datepicker' => Carbon::parse($cachedData['religious_wedding_date_time'])->format('Y-m-d H:i:s') ?? null,

            // party
            'party_address' => $cachedData['party_address'] ?? null,
            'party_city' => $cachedData['party_city'] ?? null,
            'party_country' => $cachedData['party_country'] ?? null,
            'party_datepicker' => Carbon::parse($cachedData['party_date_time'])->format('Y-m-d H:i:s') ?? null,

            // more file fields
            'background_photo_first_page' => is_array($cachedData['background_photo_first_page'] ?? null) ? head($cachedData['background_photo_first_page']) : $cachedData['background_photo_first_page'] ?? null,
            'invitation_subtitle' => $cachedData['invitation_subtitle'] ?? null,
            'title_color' => $cachedData['title_color'] ?? null,
            'subtitle_color' => $cachedData['subtitle_color'] ?? null,
            'countdown_image' => is_array($cachedData['countdown_image'] ?? null) ? head($cachedData['countdown_image']) : $cachedData['countdown_image'] ?? null,
            'countdown_text' => $cachedData['countdown_text'] ?? null,
            'countdown' => $cachedData['countdown'] ?? 'civil_wedding',
            'couple_section_image' => is_array($cachedData['couple_section_image'] ?? null) ? head($cachedData['couple_section_image']) : $cachedData['couple_section_image'] ?? null,

            // description
            'description_title' => $cachedData['description_title'] ?? null,
            'description_subtitle' => $cachedData['description_subtitle'] ?? null,
            'description_section_text' => $cachedData['description_section_text'] ?? null,

            // extra text
            'additional_question' => $cachedData['additional_question'] ?? null,
            'additional_text' => $cachedData['additional_text'] ?? null,
            'confirmation_deadline' => Carbon::parse($cachedData['confirmation_deadline'])->format('Y-m-d H:i:s') ?? null,

            'whatsapp_thumbnail' => is_array($cachedData['whatsapp_thumbnail'] ?? null) ? head($cachedData['whatsapp_thumbnail']) : $cachedData['whatsapp_thumbnail'] ?? null,
            'text_displayed_when_sharing' => $cachedData['text_displayed_when_sharing'] ?? null,

            // booleans
            'need_accommodation' => (bool)($cachedData['need_accommodation'] ?? false),
            'need_vegetarian_menu' => (bool)($cachedData['need_vegetarian_menu'] ?? false),
            'possibility_to_select_nr_kids' => (bool)($cachedData['possibility_to_select_nr_kids'] ?? false),

            // foreign keys & links
            'plan_id' => $cachedData['selected_plan'] ?? null,
            'invitation_link' => $cachedData['invitation_link'] ?? null,
            'confirmation_possibility' => array_search('offer_confirmation_possibility', $cachedData['options']),
            'limit_confirmation_once' => array_search('limit_to_one_confirmation', $cachedData['options']),
            'invitation_password' => $cachedData['invitation_password'] ?? null,

//            for baptism
            'child_name' => $cachedData['child_name'] ?? null,
            'twin_name' => $cachedData['twin_name'] ?? null,
            'kids_text' => $cachedData['kids_text'] ?? null,
            'child_photo' => is_array($cachedData['child_photo'] ?? null) ? head($cachedData['child_photo']) : $cachedData['child_photo'] ?? null,
            'child_section_image' => is_array($cachedData['child_section_image'] ?? null) ? head($cachedData['child_section_image']) : $cachedData['child_section_image'] ?? null,

            // use the looked-up weddingTypeId
            'event_type_id' => 1,

            // always the authenticated user
            'user_id' => Auth::user()->id
        ];

        Invitation::create($data);

        $this->redirect(\Filament\Facades\Filament::getLoginUrl());
    }
}
