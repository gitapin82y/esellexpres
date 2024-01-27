<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('role')->comment('1=reseler,2=seller,3=user'); 
            $table->decimal('balance', 12, 2)->default(0.00);
            $table->string('avatar')->default('images/avatar.png');
            $table->string('name');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->boolean('register')->nullable()->comment('register seller');
            $table->text('type_card')->nullable();
            $table->text('photo_card')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('last_activity')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
