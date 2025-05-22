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

        // Define field groups for easier processing
        $simpleFields = [
            'name', 'email', 'secondary_email',
            'bride_first_name', 'bride_last_name',
            'groom_first_name', 'groom_last_name',
            'bride_text', 'groom_text', 'couple_text',
            'godparents', 'invitation_subtitle',
            'title_color', 'subtitle_color', 'countdown_text',
            'description_title', 'description_subtitle', 'description_section_text',
            'additional_question', 'additional_text',
            'invitation_link', 'invitation_password',
            'text_displayed_when_sharing', 'kids_text',
        ];

        $photoFields = [
            'common_photo', 'bride_photo', 'groom_photo', 'couple_photo',
            'background_photo_first_page', 'countdown_image',
            'couple_section_image', 'whatsapp_thumbnail', 'child_photo', 'child_section_image'
        ];

        $datePickers = [
            'civil_wedding_datepicker' => 'civil_wedding_date_time',
            'religious_wedding_datepicker' => 'religious_wedding_date_time',
            'party_datepicker' => 'party_date_time',
            'confirmation_deadline' => 'confirmation_deadline'
        ];

        $locationFields = [
            'civil_wedding' => ['address', 'city', 'country'],
            'religious_wedding' => ['address', 'city', 'country'],
            'party' => ['address', 'city', 'country']
        ];

        $booleanFields = [
            'need_accommodation', 'need_vegetarian_menu', 'possibility_to_select_nr_kids'
        ];

        // Initialize data array
        $data = [];

        // Process simple fields
        foreach ($simpleFields as $field) {
            $data[$field] = $cachedData[$field] ?? null;
        }

        // Process photo fields
        foreach ($photoFields as $field) {
            $data[$field] = is_array($cachedData[$field] ?? null) ? head($cachedData[$field]) : $cachedData[$field] ?? null;
        }

        // Process date fields
        foreach ($datePickers as $targetField => $sourceField) {
            $data[$targetField] = isset($cachedData[$sourceField]) ?
                Carbon::parse($cachedData[$sourceField])->format('Y-m-d H:i:s') : null;
        }

        // Process location fields
        foreach ($locationFields as $prefix => $fields) {
            foreach ($fields as $field) {
                $key = "{$prefix}_{$field}";
                $data[$key] = $cachedData[$key] ?? null;
            }
        }

        // Process boolean fields
        foreach ($booleanFields as $field) {
            $data[$field] = (bool)($cachedData[$field] ?? false);
        }

        // Add special case fields that need custom handling
        $data['individual_photo'] = $cachedData['display_type'] ?? null;
        $data['no_photo'] = isset($cachedData['no_photo']) && (bool)$cachedData['no_photo'];
        $data['parents'] = $cachedData['parent'] ?? null;
        $data['countdown'] = $cachedData['countdown'] ?? 'civil_wedding';
        $data['plan_id'] = $cachedData['selected_plan'] ?? null;
        $data['child_name'] = $cachedData['child_name'] ?? null;
        $data['twin_name'] = $cachedData['twin_name'] ?? null;
        $data['confirmation_possibility'] = array_search('offer_confirmation_possibility', $cachedData['options'] ?? []);
        $data['limit_confirmation_once'] = array_search('limit_to_one_confirmation', $cachedData['options'] ?? []);
        $data['event_type_id'] = 1;
        $data['user_id'] = Auth::user()->id;

        Invitation::create($data);

        // Clear the cache after data is saved
        Cache::forget('eventify-cached-data');

        $this->redirect(\Filament\Facades\Filament::getLoginUrl());
    }
}
