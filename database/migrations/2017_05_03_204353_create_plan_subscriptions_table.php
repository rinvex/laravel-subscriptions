<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Get users model
        $userModel = config('auth.providers.'.config('auth.guards.'.config('auth.defaults.guard').'.provider').'.model');

        Schema::create(config('rinvex.subscriptions.tables.plan_subscriptions'), function (Blueprint $table) use ($userModel) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('plan_id')->unsigned();
            $table->string('slug');
            $table->{$this->jsonable()}('name');
            $table->{$this->jsonable()}('description')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancels_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->unique('slug');
            $table->foreign('user_id')->references('id')->on((new $userModel())->getTable())
                  ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('plan_id')->references('id')->on(config('rinvex.subscriptions.tables.plans'))
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('rinvex.subscriptions.tables.plan_subscriptions'));
    }

    /**
     * Get jsonable column data type.
     *
     * @return string
     */
    protected function jsonable(): string
    {
        return DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'mysql'
               && version_compare(DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION), '5.7.8', 'ge')
            ? 'json' : 'text';
    }
}
