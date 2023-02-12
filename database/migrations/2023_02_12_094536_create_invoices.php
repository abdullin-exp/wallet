<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('from_user_id');
            $table->unsignedBigInteger('to_user_id');
            $table->unsignedBigInteger('deposit_id');
            $table->unsignedBigInteger('withdraw_id')
                ->nullable();

            $table->decimal('amount', 7, 0);

            $table->enum('status', ['processing', 'paid'])
                ->default('processing');

            $table->timestamps();

            $table->foreign('from_user_id')
                ->references('id')
                ->on('users');

            $table->foreign('to_user_id')
                ->references('id')
                ->on('users');

            $table->foreign('deposit_id')
                ->references('id')
                ->on('wallets');

            $table->foreign('withdraw_id')
                ->references('id')
                ->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
