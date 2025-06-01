<?php

namespace App\Livewire;

use App\Models\InvitationTemplate;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Carbon\Carbon;

class PeaceInvitation extends Component
{
    // Properties from template
    public string $brideFirstName = '';
    public string $brideLastName = '';
    public string $groomFirstName = '';
    public string $groomLastName = '';
    public string $weddingDate = '';
    public string $weddingLocation = '';
    public string $coupleDescription = '';
    public bool $showCoupleAsSeparate = true;
    public string $brideDescription = '';
    public string $groomDescription = '';
    public string $mainBackgroundImage = '';
    public string $coupleImage = '';
    public string $brideImage = '';
    public string $groomImage = '';
    public string $countdownBackgroundImage = '';
    public string $rsvpBackgroundImage = '';
    public string $godparentsTitle = '';
    public string $godparentsDescription = '';
    public string $godparents = '';
    public string $countdownTitle = '';
    public string $eventTitle = '';
    public string $eventDescription = '';
    public string $whenWhereTitle = '';
    public array $events = [];
    public string $rsvpTitle = '';
    public string $rsvpSubtitle = '';
    public int $attendees = 1;
    public string $rsvpMessage = '';
    public bool $isAttending = false;

    public function mount(): void
    {
        $cachedData = Cache::get('eventify-cached-data');
        $template = InvitationTemplate::where('class_name', self::class)->first();

        if (!$template) {
            throw new \Exception('Template not found for ' . self::class);
        }

        if ($cachedData) {
            // Basic fields
            $this->brideFirstName = $cachedData['bride_first_name'] ?? $template->bride_first_name;
            $this->brideLastName = $cachedData['bride_last_name'] ?? $template->bride_last_name;
            $this->groomFirstName = $cachedData['groom_first_name'] ?? $template->groom_first_name;
            $this->groomLastName = $cachedData['groom_last_name'] ?? $template->groom_last_name;
            $this->weddingDate = $cachedData['religious_wedding_datepicker'] ?? $template->religious_wedding_datepicker;

            // Location fields
            if (isset($cachedData['religious_wedding_address'], $cachedData['religious_wedding_city'])) {
                $this->weddingLocation = "{$cachedData['religious_wedding_address']}, {$cachedData['religious_wedding_city']}";
            } else {
                $this->weddingLocation = "{$template->religious_wedding_address}, {$template->religious_wedding_city}";
            }

            // Description fields
            $this->coupleDescription = $cachedData['couple_text'] ?? $template->couple_text;
            $this->brideDescription = $cachedData['bride_text'] ?? $template->bride_text;
            $this->groomDescription = $cachedData['groom_text'] ?? $template->groom_text;

            // Image fields with UUID handling
            $this->mainBackgroundImage = $cachedData['background_photo_first_page'] ?? $template->background_photo_first_page;
            $this->coupleImage = isset($cachedData['common_photo']) ?
                $cachedData['common_photo'][array_key_first($cachedData['common_photo'])] :
                $template->couple_photo;
            $this->brideImage = isset($cachedData['bride_photo']) ?
                $cachedData['bride_photo'][array_key_first($cachedData['bride_photo'])] :
                $template->bride_photo;
            $this->groomImage = isset($cachedData['groom_photo']) ?
                $cachedData['groom_photo'][array_key_first($cachedData['groom_photo'])] :
                $template->groom_photo;
            $this->countdownBackgroundImage = $cachedData['countdown_image'] ?? $template->countdown_image;
            $this->rsvpBackgroundImage = $cachedData['countdown_image'] ?? $template->countdown_image;

            // Additional content fields
            $this->godparents = $cachedData['godparents'] ?? $template->godparents;
            $this->countdownTitle = $cachedData['countdown_text'] ?? $template->countdown_text;
            $this->eventTitle = $cachedData['description_title'] ?? $template->description_title;
            $this->eventDescription = $cachedData['description_section_text'] ?? $template->description_section_text;

            // Set default titles if not present in either source
            $this->godparentsTitle = 'Our Godparents';
            $this->godparentsDescription = 'We are blessed to have these special people in our lives.';
            $this->whenWhereTitle = 'Join Us For Our Special Day';
            $this->rsvpTitle = 'Will You Join Us?';
            $this->rsvpSubtitle = "Please let us know if you can attend by " . Carbon::parse($this->weddingDate)->subDays(30)->format('F j, Y');

            // Events array
            $this->events = [
                [
                    'title' => 'Religious Ceremony',
                    'date' => $cachedData['religious_wedding_datepicker'] ?? $template->religious_wedding_datepicker,
                    'location' => isset($cachedData['religious_wedding_address']) ?
                        "{$cachedData['religious_wedding_address']}, {$cachedData['religious_wedding_city']}, {$cachedData['religious_wedding_country']}" :
                        "{$template->religious_wedding_address}, {$template->religious_wedding_city}, {$template->religious_wedding_country}",
                    'image' => '/templateImages/religious-wedding.avif',
                    'description' => 'The ceremony will be followed by photos on the church grounds.'
                ],
                [
                    'title' => 'Civil Ceremony',
                    'date' => $cachedData['civil_wedding_datepicker'] ?? $template->civil_wedding_datepicker,
                    'location' => isset($cachedData['civil_wedding_address']) ?
                        "{$cachedData['civil_wedding_address']}, {$cachedData['civil_wedding_city']}, {$cachedData['civil_wedding_country']}" :
                        "{$template->civil_wedding_address}, {$template->civil_wedding_city}, {$template->civil_wedding_country}",
                    'image' => '/templateImages/ceremony-wedding-1.jpg',
                    'description' => 'A small civil ceremony with close family.'
                ],
                [
                    'title' => 'Reception',
                    'date' => $cachedData['party_datepicker'] ?? $template->party_datepicker,
                    'location' => isset($cachedData['party_address']) ?
                        "{$cachedData['party_address']}, {$cachedData['party_city']}, {$cachedData['party_country']}" :
                        "{$template->party_address}, {$template->party_city}, {$template->party_country}",
                    'image' => '/templateImages/reception-1.jpg',
                    'description' => 'Dinner, dancing, and celebration until midnight.'
                ]
            ];
        } else {
            // If no cached data, use template data directly
            $this->setDefaultValues($template);
        }
    }

    private function setDefaultValues(InvitationTemplate $template): void
    {
        $this->brideFirstName = $template->bride_first_name;
        $this->brideLastName = $template->bride_last_name;
        $this->groomFirstName = $template->groom_first_name;
        $this->groomLastName = $template->groom_last_name;
        $this->weddingDate = $template->religious_wedding_datepicker;
        $this->weddingLocation = "{$template->religious_wedding_address}, {$template->religious_wedding_city}";
        $this->coupleDescription = $template->couple_text;
        $this->brideDescription = $template->bride_text;
        $this->groomDescription = $template->groom_text;
        $this->mainBackgroundImage = $template->background_photo_first_page;
        $this->coupleImage = $template->couple_photo;
        $this->brideImage = $template->bride_photo;
        $this->groomImage = $template->groom_photo;
        $this->countdownBackgroundImage = $template->countdown_image;
        $this->rsvpBackgroundImage = $template->countdown_image;
        $this->godparents = $template->godparents;
        $this->countdownTitle = $template->countdown_text;
        $this->eventTitle = $template->description_title;
        $this->eventDescription = $template->description_section_text;

        // Set default titles
        $this->godparentsTitle = 'Our Godparents';
        $this->godparentsDescription = 'We are blessed to have these special people in our lives.';
        $this->whenWhereTitle = 'Join Us For Our Special Day';
        $this->rsvpTitle = 'Will You Join Us?';
        $this->rsvpSubtitle = "Please let us know if you can attend by " . Carbon::parse($this->weddingDate)->subDays(30)->format('F j, Y');

        $this->events = [
            [
                'title' => 'Religious Ceremony',
                'date' => $template->religious_wedding_datepicker,
                'location' => "{$template->religious_wedding_address}, {$template->religious_wedding_city}, {$template->religious_wedding_country}",
                'image' => '/images/wedding/church.jpg',
                'description' => 'The ceremony will be followed by photos on the church grounds.'
            ],
            [
                'title' => 'Civil Ceremony',
                'date' => $template->civil_wedding_datepicker,
                'location' => "{$template->civil_wedding_address}, {$template->civil_wedding_city}, {$template->civil_wedding_country}",
                'image' => '/images/wedding/civil.jpg',
                'description' => 'A small civil ceremony with close family.'
            ],
            [
                'title' => 'Reception',
                'date' => $template->party_datepicker,
                'location' => "{$template->party_address}, {$template->party_city}, {$template->party_country}",
                'image' => '/images/wedding/reception.jpg',
                'description' => 'Dinner, dancing, and celebration until midnight.'
            ]
        ];
    }

    public function submitRSVP($attending): void
    {
        $this->isAttending = $attending;
        session()->flash('message', $attending ? 'Thank you! Your attendance has been confirmed.' : 'Thank you for letting us know.');
    }

    public function getRemainingDaysProperty(): float
    {
        $weddingDate = Carbon::parse($this->weddingDate);
        return $weddingDate->diffInDays(Carbon::now());
    }

    public function render()
    {
        return view('livewire.peace-invitation')
            ->layout('layouts.guest');
    }
}
