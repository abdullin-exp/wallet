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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('transactions_from_user_id_foreign');
            $table->dropForeign('transactions_to_user_id_foreign');
            $table->dropForeign('transactions_from_wallet_id_foreign');
            $table->dropForeign('transactions_to_wallet_id_foreign');

            $table->dropColumn(['from_user_id', 'to_user_id', 'from_wallet_id', 'to_wallet_id']);
            $table->dropColumn(['confirmed', 'scheduled_at']);

            $table->unsignedBigInteger('wallet_id')
                ->after('id');

            $table->foreign('wallet_id')
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
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('from_user_id')
                ->nullable();
            $table->unsignedBigInteger('to_user_id');

            $table->foreign('from_user_id')
                ->references('id')
                ->on('users');

            $table->foreign('to_user_id')
                ->references('id')
                ->on('users');

            $table->unsignedBigInteger('from_wallet_id')
                ->nullable();
            $table->unsignedBigInteger('to_wallet_id');

            $table->foreign('from_wallet_id')
                ->references('id')
                ->on('wallets');

            $table->foreign('to_wallet_id')
                ->references('id')
                ->on('wallets');
        });
    }
};
