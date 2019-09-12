<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Hamraa\Payment\Port;
use Hamraa\Payment\GatewayResolver;
use Hamraa\Payment\Constants;

class CreatePaymentTransactionsTable extends Migration
{
    function getTable()
    {
        return config('payment.table', 'payment_transactions');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getTable(), function (Blueprint $table) {
            $table->engine = "innoDB";
            $table->bigIncrements('id');
            $table->text('description')->nullable();
            $table->enum('port', [
                Constants::MELLAT,
                Constants::SADAD,
                Constants::ZARINPAL,
                Constants::PAYLINE,
                Constants::JAHANPAY,
                Constants::PARSIAN,
                Constants::PASARGAD,
                Constants::SAMAN,
                Constants::ASANPARDAKHT,
                Constants::PAYPAL,
                Constants::PAYIR
            ]);
            $table->decimal('price', 15, 2);
            $table->string('ref_id', 100)->nullable();
            $table->string('tracking_code', 50)->nullable();
            $table->string('card_number', 50)->nullable();
            $table->enum('status', [
                Constants::TRANSACTION_INIT,
                Constants::TRANSACTION_SUCCEED,
                Constants::TRANSACTION_FAILED,
            ])->default(Constants::TRANSACTION_INIT);
            $table->string('ip', 20)->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->getTable());
    }
}
