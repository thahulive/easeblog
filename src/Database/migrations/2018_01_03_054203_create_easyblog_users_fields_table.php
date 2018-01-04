<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEasyblogUsersFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('email')->default('users/default.png');
            }
            if (!Schema::hasColumn('users', 'about')) {
                $table->text('about')->nullable();
            }
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
          if (!Schema::hasColumn('users', 'name')) {
              $table->string('name');
          }
          if (Schema::hasColumn('users', 'avatar')) {
              $table->dropColumn('avatar');
          }
          if (Schema::hasColumn('users', 'about')) {
              $table->dropColumn('about');
          }
          if (Schema::hasColumn('users', 'first_name')) {
              $table->dropColumn('first_name');
          }
          if (Schema::hasColumn('users', 'last_name')) {
              $table->dropColumn('last_name');
          }
        });
    }
}
