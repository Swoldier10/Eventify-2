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
        Schema::table('invitations', function (Blueprint $table) {
            $table->string('child_name')->nullable();
            $table->string('twin_name')->nullable();
            $table->longText('kids_text')->nullable();
            $table->string('child_photo')->nullable();
            $table->string('child_section_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn([
                'child_name',
                'twin_name',
                'kids_text',
                'child_photo',
                'child_section_image',
            ]);
        });
    }
};
