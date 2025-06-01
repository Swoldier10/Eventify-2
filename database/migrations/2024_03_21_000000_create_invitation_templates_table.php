<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitation_templates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->string('bride_first_name');
            $table->string('bride_last_name');
            $table->string('groom_first_name');
            $table->string('groom_last_name');
            $table->string('individual_photo')->nullable();
            $table->string('common_photo')->nullable();
            $table->string('no_photo')->nullable();
            $table->string('bride_photo')->nullable();
            $table->longText('bride_text')->nullable();
            $table->string('groom_photo')->nullable();
            $table->longText('groom_text')->nullable();
            $table->string('couple_photo')->nullable();
            $table->longText('couple_text')->nullable();
            $table->longText('godparents')->nullable();
            $table->longText('parents')->nullable();
            $table->string('civil_wedding_address')->nullable();
            $table->string('civil_wedding_city')->nullable();
            $table->string('civil_wedding_country')->nullable();
            $table->timestamp('civil_wedding_datepicker')->nullable();
            $table->string('religious_wedding_address')->nullable();
            $table->string('religious_wedding_city')->nullable();
            $table->string('religious_wedding_country')->nullable();
            $table->timestamp('religious_wedding_datepicker')->nullable();
            $table->string('party_address')->nullable();
            $table->string('party_city')->nullable();
            $table->string('party_country')->nullable();
            $table->timestamp('party_datepicker')->nullable();
            $table->string('background_photo_first_page')->nullable();
            $table->string('invitation_subtitle')->nullable();
            $table->string('title_color')->nullable();
            $table->string('subtitle_color')->nullable();
            $table->string('countdown_image')->nullable();
            $table->longText('countdown_text')->nullable();
            $table->enum('countdown', ['civil_wedding', 'religious_wedding', 'party'])->default('party');
            $table->string('couple_section_image')->nullable();
            $table->string('description_title')->nullable();
            $table->string('description_subtitle')->nullable();
            $table->longText('description_section_text')->nullable();
            $table->longText('additional_question')->nullable();
            $table->longText('additional_text')->nullable();
            $table->timestamp('confirmation_deadline')->nullable();
            $table->string('whatsapp_thumbnail')->nullable();
            $table->longText('text_displayed_when_sharing')->nullable();
            $table->string('need_accommodation')->nullable();
            $table->string('need_vegetarian_menu')->nullable();
            $table->string('possibility_to_select_nr_kids')->nullable();
            $table->boolean('confirmation_possibility')->default(true);
            $table->boolean('limit_confirmation_once')->default(false);
            $table->string('invitation_password')->nullable();
            $table->string('class_name');

            // Child fields
            $table->string('child_name')->nullable();
            $table->string('twin_name')->nullable();
            $table->longText('kids_text')->nullable();
            $table->string('child_photo')->nullable();
            $table->string('child_section_image')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitation_templates');
    }
}; 