<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturePlanTable extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn(config('rinvex.subscriptions.tables.features'), 'plan_id')) {
            Schema::table(config('rinvex.subscriptions.tables.features'), function (Blueprint $table) {
                $table->dropForeign('plan_id');
                $table->dropColumn('plan_id');
            });
        }

        Schema::create(config('rinvex.subscriptions.tables.feature_plan'), function (Blueprint $table) {
            $table->foreignId('plan_id')
                ->constrained(config('rinvex.subscriptions.tables.plans'))
                ->cascadeOnDelete();

            $table->foreignId('feature_id')
                ->constrained(config('rinvex.subscriptions.tables.features'))
                ->cascadeOnDelete();

            $table->primary(['plan_id', 'feature_id']);
            $table->unique(['plan_id', 'feature_id']);

            $table->string('value');
            $table->unsignedSmallInteger('resettable_period')->default(0);
            $table->string('resettable_interval')->default('month');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('rinvex.subscriptions.tables.feature_plan'));
    }
}
