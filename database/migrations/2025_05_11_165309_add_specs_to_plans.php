<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')
                ->index()
                ->nullable()
                ->constrained('plans')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('feature_id')
                ->index()
                ->nullable()
                ->constrained('features')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        foreach (
            [
                0 => 'Simple invitation in photo format jpg/png',
                1 => 'Invitation sharing link',
                2 => 'Free template modification',
                3 => 'Capture invitation responses',
                4 => 'Export invitation list (Excel/CSV)',
                5 => 'Custom questions for guests',
                6 => 'Real-time response notifications',
                7 => 'Guest table management system'

            ] as $index => $feature) {

            $tempFeature = \App\Models\Feature::create([
                'name' => $feature
            ]);

            if (in_array($index, [0, 1])) {
                $plan = \App\Models\Plan::where('name', 'Basic')->first();
                $plan->features()->attach($tempFeature->id);
            }

            if (in_array($index, [2, 3, 4])) {
                $plan = \App\Models\Plan::where('name', 'Pro')->first();
                $plan->features()->attach($tempFeature->id);
            }

            if (in_array($index, [2, 3, 4, 5, 6, 7])) {
                $plan = \App\Models\Plan::where('name', 'Premium')->first();
                $plan->features()->attach($tempFeature->id);
            }


        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            //
        });
    }
};
