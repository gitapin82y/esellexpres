<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_balances', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->decimal('total',12,2);
            $table->string('message')->nullable();
            $table->string('proof')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('number')->nullable();
            $table->enum('submission', ['Withdraw', 'Top Up']);
            $table->enum('status', ['Pending', 'Success', 'Failure']);
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
        Schema::dropIfExists('transaction_balances');
    }
}
