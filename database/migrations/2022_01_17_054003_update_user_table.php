<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //user table update
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['name','email_verified_at', 'password', 'remember_token']);
            $table->string('first_name')->nullable(false)->before("email");
            $table->string('last_name')->nullable(false)->after("first_name");
            $table->string('phone_number')->nullable(false)->after("email");
            $table->text('address')->nullable()->after("phone_number");
            $table->date('date_of_birth')->nullable()->after("address");
            $table->string('is_vaccinated')->nullable()->after("date_of_birth");
            $table->string('vaccine_name')->nullable()->after("is_vaccinated");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->befoe('email');
            $table->string('email_verified_at')->after('email');
            $table->string('password');
            $table->string('remember_token');
            $table->dropColumn(['first_name', 'last_name', 'phone_number', 'address', 'date_of_birth', 'is_vaccinated', 'vaccine_name']);
        });
    }
}
