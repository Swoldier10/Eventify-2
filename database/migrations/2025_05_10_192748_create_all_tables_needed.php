<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('custom_fields')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string(config('filament-edit-profile.avatar_column', 'avatar_url'))->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_id')->nullable()->index();
            $table->string('pm_type')->nullable();
            $table->string('pm_last_four', 4)->nullable();
            $table->timestamp('trial_ends_at')->nullable();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('type');
            $table->string('stripe_id')->unique();
            $table->string('stripe_status');
            $table->string('stripe_price')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'stripe_status']);
        });

        Schema::create('subscription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id');
            $table->string('stripe_id')->unique();
            $table->string('stripe_product');
            $table->string('stripe_price');
            $table->integer('quantity')->nullable();
            $table->timestamps();

            $table->index(['subscription_id', 'stripe_price']);
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->decimal('price', 8, 2);
        });

        DB::table('plans')->insert([
            ['name' => 'Basic', 'price' => 59.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pro', 'price' => 199.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Premium', 'price' => 299.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::create('event_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
        });

        DB::table('event_types')->insert([
            ['name' => 'Wedding', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Christening', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Party', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->string('email');
            $table->string('secondary_email')->nullable();
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
            $table->string('invitation_link')->unique();
            $table->boolean('confirmation_possibility')->default(true);
            $table->boolean('limit_confirmation_once')->default(false);
            $table->string('invitation_password')->nullable();

            // Foreign keys
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });

        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('nr_of_people')->nullable();
            $table->string('name')->nullable();
            $table->string('partner_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->integer('nr_of_kids')->nullable();
            $table->boolean('need_accomodation')->nullable();
            $table->boolean('vegetarian_menu')->nullable();
            $table->longText('note')->nullable();
            $table->longText('additional_question_answer')->nullable();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('invitation_id')->constrained()->onDelete('cascade');
        });

        Schema::create('table_arrangements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('invitation_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('max_seats_per_table')->default(10);
        });

        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('invitation_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('table_number'); // e.g., table 1, 2, 3...
        });

        Schema::create('guest_table', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('guest_id')->constrained()->onDelete('cascade');
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('seats_reserved')->default(1); // Number of seats this guest+party takes

            $table->unique(['guest_id', 'table_id']);
        });

        Schema::create('unconfirmed_guests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('nr_of_people')->nullable();
            $table->string('name')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('note')->nullable();
            $table->text('additional_question_answer')->nullable();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('invitation_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unconfirmed_guests');

        Schema::dropIfExists('guest_table');

        Schema::dropIfExists('tables');

        Schema::dropIfExists('table_arrangements');

        Schema::dropIfExists('guests');

        Schema::dropIfExists('invitations');

        Schema::dropIfExists('event_types');

        Schema::dropIfExists('plans');

        Schema::dropIfExists('subscription_items');

        Schema::dropIfExists('subscriptions');

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex([
                'stripe_id',
            ]);

            $table->dropColumn([
                'stripe_id',
                'pm_type',
                'pm_last_four',
                'trial_ends_at',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(config('filament-edit-profile.avatar_column', 'avatar_url'));
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('custom_fields');
        });
    }
};
