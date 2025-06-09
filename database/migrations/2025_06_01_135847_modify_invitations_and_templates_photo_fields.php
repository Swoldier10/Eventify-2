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
        // First, modify the invitations table
        Schema::table('invitations', function (Blueprint $table) {
            // Drop the old columns
            $table->dropColumn(['individual_photo', 'no_photo']);
            
            // Add the new enum column
            $table->enum('celebrants_photo_type', ['individual_photo', 'common_photo', 'no_photo'])
                ->default('individual_photo')
                ->after('groom_last_name');
        });

        // Then, modify the invitation_templates table
        Schema::table('invitation_templates', function (Blueprint $table) {
            // Drop the old columns
            $table->dropColumn(['individual_photo', 'no_photo']);
            
            // Add the new enum column
            $table->enum('celebrants_photo_type', ['individual_photo', 'common_photo', 'no_photo'])
                ->default('individual_photo')
                ->after('groom_last_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert changes in invitations table
        Schema::table('invitations', function (Blueprint $table) {
            // Remove the new enum column
            $table->dropColumn('celebrants_photo_type');
            
            // Add back the old columns
            $table->boolean('individual_photo')->default(true)->after('groom_last_name');
            $table->boolean('no_photo')->default(false)->after('individual_photo');
        });

        // Revert changes in invitation_templates table
        Schema::table('invitation_templates', function (Blueprint $table) {
            // Remove the new enum column
            $table->dropColumn('celebrants_photo_type');
            
            // Add back the old columns
            $table->boolean('individual_photo')->default(true)->after('groom_last_name');
            $table->boolean('no_photo')->default(false)->after('individual_photo');
        });
    }
};
