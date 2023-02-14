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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('from_user_id')
                ->nullable();
            $table->unsignedBigInteger('to_user_id');
            $table->unsignedBigInteger('from_wallet_id')
                ->nullable();
            $table->unsignedBigInteger('to_wallet_id');

            $table->foreign('from_user_id')
                ->references('id')
                ->on('users');

            $table->foreign('to_user_id')
                ->references('id')
                ->on('users');

            $table->foreign('from_wallet_id')
                ->references('id')
                ->on('wallets');

            $table->foreign('to_wallet_id')
                ->references('id')
                ->on('wallets');

            $table->decimal('amount', 7, 0);

            $table->boolean('confirmed');

            $table->date('scheduled_date')
                ->nullable();

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
        Schema::dropIfExists('transfers');
    }
};
