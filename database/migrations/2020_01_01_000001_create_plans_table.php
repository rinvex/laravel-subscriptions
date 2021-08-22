<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('rinvex.subscriptions.tables.plans'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('slug');
            $table->json('name');
            $table->json('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('price')->default('0.00');
            $table->decimal('signup_fee')->default('0.00');
            $table->string('currency', 3);
            $table->smallInteger('trial_period')->unsigned()->default(0);
            $table->string('trial_interval')->default('day');
            $table->smallInteger('invoice_period')->unsigned()->default(0);
            $table->string('invoice_interval')->default('month');
            $table->smallInteger('grace_period')->unsigned()->default(0);
            $table->string('grace_interval')->default('day');
            $table->tinyInteger('prorate_day')->unsigned()->nullable();
            $table->tinyInteger('prorate_period')->unsigned()->nullable();
            $table->tinyInteger('prorate_extend_due')->unsigned()->nullable();
            $table->smallInteger('active_subscribers_limit')->unsigned()->nullable();
            $table->mediumInteger('sort_order')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('rinvex.subscriptions.tables.plans'));
    }
}
