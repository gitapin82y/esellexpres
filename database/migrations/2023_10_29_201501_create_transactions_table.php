<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->integer('delivery_service_id');
            $table->integer('user_id');
            $table->string('uuid');
            $table->string('name');
            $table->string('email');
            $table->enum('is_confirmed', ['Y', 'N'])->default('N');
            $table->string('phone');
            $table->string('address');
            $table->integer('total_quantity');
            $table->decimal('transaction_total',12,2);
            $table->decimal('profit',12,2);
            $table->decimal('tax', 10, 2)->default(10.00);
            $table->string('status')->default('Waiting process');
            $table->string('resi')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
