<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCashierColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->string('pm_type')->nullable();
        //     $table->string('pm_last_four', 4)->nullable();
        //     $table->timestamp('trial_ends_at')->nullable();
        //     $table->string('address')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn([
        //         'pm_type',
        //         'pm_last_four',
        //         'trial_ends_at',
        //         'address',
        //     ]);
        // });
    }
}
