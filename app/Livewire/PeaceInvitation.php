<?php

namespace App\Livewire;

use App\Models\Invitation;
use App\Models\InvitationTemplate;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules\In;
use Livewire\Component;
use Carbon\Carbon;

class PeaceInvitation extends Component
{
    public array $data = [];
    public array $events = [];
    public $attendees;
    public $rsvpMessage;
    public bool $modalMode = false;

    public function mount($modalMode = false): void
    {
        $this->modalMode = $modalMode;
        $template = InvitationTemplate::where('class_name', self::class)->first();

        if (!$template) {
            throw new \Exception('Template not found for ' . self::class);
        }

        if (Auth::check() && Filament::getTenant()) {
            $invitation = Filament::getTenant();

            foreach ([
                         ...Invitation::$generalInfoFields,
                         ...Invitation::$celebrantsDetailsFields,
                         ...Invitation::$locationFields,
                         ...Invitation::$advancedCustomizationFields,
                         ...Invitation::$guestsSettingsFields
                     ] as $field) {
                $this->data[$field] = $invitation[$field] ?? $template->{$field};
            }
        } else {
            $cachedData = Cache::get('eventify-cached-data');
            foreach ([
                         ...Invitation::$generalInfoFields,
                         ...Invitation::$celebrantsDetailsFields,
                         ...Invitation::$locationFields,
                         ...Invitation::$advancedCustomizationFields,
                         ...Invitation::$guestsSettingsFields
                     ] as $field) {
                if (isset($cachedData[$field]) && is_array($cachedData[$field])){
                    $this->data[$field] = head($cachedData[$field]);
                }else{
                    $this->data[$field] = $cachedData[$field] ?? $template->{$field};
                }
            }
        }

        // Prepare events data
        $this->events = [
            [
                'title' => __('translations.Civil Wedding'),
                'date' => $this->data['civil_wedding_datepicker'],
                'location' => $this->data['civil_wedding_address'] . ', ' . $this->data['civil_wedding_city'],
                'description' => __('translations.Join us for our civil ceremony'),
                'image' => $this->data['background_photo_first_page']
            ],
            [
                'title' => __('translations.Religious Wedding'),
                'date' => $this->data['religious_wedding_datepicker'],
                'location' => $this->data['religious_wedding_address'] . ', ' . $this->data['religious_wedding_city'],
                'description' => __('translations.Join us for our religious ceremony'),
                'image' => $this->data['background_photo_first_page']
            ],
            [
                'title' => __('translations.Wedding Party'),
                'date' => $this->data['party_datepicker'],
                'location' => $this->data['party_address'] . ', ' . $this->data['party_city'],
                'description' => __('translations.Join us for our wedding party'),
                'image' => $this->data['background_photo_first_page']
            ]
        ];
    }

    public function submitRSVP($attending): void
    {
        if ($attending) {
            $this->validate([
                'attendees' => 'required|numeric|min:1',
            ]);
        }

        session()->flash('message', $attending ?
            __('translations.Thank you for confirming your attendance!') :
            __('translations.Thank you for letting us know you cannot attend.')
        );
    }

    public function getRemainingDaysProperty(): float
    {
        $weddingDate = Carbon::parse($this->weddingDate);
        return $weddingDate->diffInDays(Carbon::now());
    }

    public function render()
    {
        return view('livewire.peace-invitation', [
            'events' => $this->events,
            'modalMode' => $this->modalMode,
            'translations' => [
                'whenWhereTitle' => __('translations.When & Where'),
                'rsvpTitle' => __('translations.RSVP'),
                'rsvpSubtitle' => __('translations.Please confirm your attendance')
            ]
        ])->layout($this->modalMode ? null : 'layouts.guest');
    }
}
