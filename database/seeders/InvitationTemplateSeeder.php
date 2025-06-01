<?php

namespace Database\Seeders;

use App\Livewire\PeaceInvitation;
use App\Models\InvitationTemplate;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InvitationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        InvitationTemplate::create([
            'name' => 'Peace Template',
            'bride_first_name' => 'Sarah',
            'bride_last_name' => 'Johnson',
            'groom_first_name' => 'Michael',
            'groom_last_name' => 'Davis',
            'bride_text' => 'Sarah loves painting, hiking, and spending time with family. She works as an architect and dreams of designing her own home someday.',
            'groom_text' => 'Michael is passionate about music, cooking, and photography. He works as a software engineer and enjoys creating apps in his spare time.',
            'couple_text' => 'We are thrilled to celebrate our special day with you. Our journey together has been amazing, and we can\'t wait to begin this new chapter surrounded by our loved ones.',
            'godparents' => json_encode([
                ['name' => 'Robert & Jennifer Smith'],
                ['name' => 'David & Lisa Wilson'],
                ['name' => 'Thomas & Emma Brown']
            ]),
            'religious_wedding_address' => 'St. Mary\'s Church, 123 Faith Street',
            'religious_wedding_city' => 'New York',
            'religious_wedding_country' => 'USA',
            'religious_wedding_datepicker' => Carbon::parse('2024-10-15 11:00:00'),
            'civil_wedding_address' => 'City Hall, 456 Government Plaza',
            'civil_wedding_city' => 'New York',
            'civil_wedding_country' => 'USA',
            'civil_wedding_datepicker' => Carbon::parse('2024-10-14 14:00:00'),
            'party_address' => 'Grand Hotel, 789 Celebration Avenue',
            'party_city' => 'New York',
            'party_country' => 'USA',
            'party_datepicker' => Carbon::parse('2024-10-15 17:00:00'),
            'background_photo_first_page' => '/images/wedding/background.jpg',
            'couple_photo' => '/images/wedding/couple.jpg',
            'bride_photo' => '/images/wedding/bride.jpg',
            'groom_photo' => '/images/wedding/groom.jpg',
            'countdown_image' => '/images/wedding/countdown-bg.jpg',
            'countdown_text' => 'Counting Down to Our Big Day',
            'description_title' => 'Our Love Story',
            'description_section_text' => 'We met five years ago at a mutual friend\'s birthday party. After two years of dating, Michael proposed during a surprise trip to Paris. Now, we\'re excited to begin our forever together and we want you to be part of our special day.',
            'confirmation_deadline' => Carbon::parse('2024-09-15'),
            'class_name' => PeaceInvitation::class,
        ]);
    }
} 