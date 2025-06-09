<?php

namespace App\Livewire;

use App\Models\Invitation;
use App\Models\InvitationTemplate;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Carbon\Carbon;

class NaturalInvitation extends Component
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

        // Default template data for Natural invitation
        $defaultData = [
            'bride_first_name' => 'Eva',
            'bride_last_name' => 'Georgescu',
            'groom_first_name' => 'Albert',
            'groom_last_name' => 'Georgescu',
            'bride_text' => 'Eva este o persoană iubitoare de natură, pasionată de călătorii și fotografie. Lucrează ca designer de interior și își dorește să creeze spații care să inspire.',
            'groom_text' => 'Albert este un iubitor al muzicii, al cărților și al activităților în aer liber. Lucrează ca inginer și visează să-și construiască propria casă în natură.',
            'couple_text' => 'Povestea noastră de dragoste a început într-o cafenea din centrul Bucureștiului. După trei ani frumoși împreună, Albert mi-a făcut propunerea într-o drumeție în munți. Acum suntem pregătiți să începem o nouă aventură împreună.',
            'religious_wedding_address' => 'Mănăstirea Casin, Bulevardul Mărăști 16',
            'religious_wedding_city' => 'București',
            'religious_wedding_country' => 'România',
            'religious_wedding_datepicker' => '2025-07-09 16:00:00',
            'civil_wedding_address' => 'Oficiul stării civile, Sector 1',
            'civil_wedding_city' => 'București',
            'civil_wedding_country' => 'România',
            'civil_wedding_datepicker' => '2025-07-09 13:00:00',
            'party_address' => 'Restaurant Oliviers Mediterranean, Strada Clucerului',
            'party_city' => 'București',
            'party_country' => 'România',
            'party_datepicker' => '2025-07-09 19:00:00',
            'background_photo_first_page' => '/images/wedding/background.jpg',
            'couple_photo' => '/images/wedding/natural-couple.jpg',
            'bride_photo' => '/images/wedding/natural-bride.jpg',
            'groom_photo' => '/images/wedding/natural-groom.jpg',
            'countdown_text' => 'Vom deveni o familie în',
            'confirmation_possibility' => true,
            'celebrants_photo_type' => 'individual_photo',
        ];

        if (Auth::check() && Filament::getTenant()) {
            $invitation = Filament::getTenant();

            foreach ([
                         ...Invitation::$generalInfoFields,
                         ...Invitation::$celebrantsDetailsFields,
                         ...Invitation::$locationFields,
                         ...Invitation::$advancedCustomizationFields,
                         ...Invitation::$guestsSettingsFields
                     ] as $field) {
                $this->data[$field] = $invitation[$field] ?? ($template?->{$field} ?? $defaultData[$field] ?? null);
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
                } else {
                    $this->data[$field] = $cachedData[$field] ?? ($template?->{$field} ?? $defaultData[$field] ?? null);
                }
            }
        }

        // Ensure we have fallback values for missing data
        foreach ($defaultData as $key => $value) {
            if (!isset($this->data[$key]) || $this->data[$key] === null) {
                $this->data[$key] = $value;
            }
        }

        // Prepare events data
        $this->events = [
            [
                'title' => __('translations.Civil Wedding') ?? 'Cununie civilă',
                'date' => $this->data['civil_wedding_datepicker'],
                'location' => $this->data['civil_wedding_address'] . ', ' . $this->data['civil_wedding_city'],
                'description' => __('translations.Join us for our civil ceremony') ?? 'Alătură-te ceremoniei civile',
                'image' => $this->data['background_photo_first_page']
            ],
            [
                'title' => __('translations.Religious Wedding') ?? 'Cununie religioasă',
                'date' => $this->data['religious_wedding_datepicker'],
                'location' => $this->data['religious_wedding_address'] . ', ' . $this->data['religious_wedding_city'],
                'description' => __('translations.Join us for our religious ceremony') ?? 'Alătură-te ceremoniei religioase',
                'image' => $this->data['background_photo_first_page']
            ],
            [
                'title' => __('translations.Wedding Party') ?? 'Petrecerea',
                'date' => $this->data['party_datepicker'],
                'location' => $this->data['party_address'] . ', ' . $this->data['party_city'],
                'description' => __('translations.Join us for our wedding party') ?? 'Alătură-te petrecerii de nuntă',
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
        $weddingDate = Carbon::parse($this->data['religious_wedding_datepicker']);
        return $weddingDate->diffInDays(Carbon::now());
    }

    public function render()
    {
        return view('livewire.natural-invitation', [
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
