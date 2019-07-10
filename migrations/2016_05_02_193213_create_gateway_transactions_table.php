<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Hamraa\Payment\PortAbstract;
use Hamraa\Payment\GatewayResolver;
use Hamraa\Payment\Gateways;

class CreateGatewayTransactionsTable extends Migration
{
	function getTable()
	{
		return config('gateway.table', 'gateway_transactions');
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
			$table->unsignedBigInteger('id', true);
			$table->enum('port', [
				Gateways::MELLAT,
				Gateways::SADAD,
				Gateways::ZARINPAL,
				Gateways::PAYLINE,
				Gateways::JAHANPAY,
				Gateways::PARSIAN,
				Gateways::PASARGAD,
				Gateways::SAMAN,
				Gateways::ASANPARDAKHT,
				Gateways::PAYPAL,
				Gateways::PAYIR
			]);
			$table->decimal('price', 15, 2);
			$table->string('ref_id', 100)->nullable();
			$table->string('tracking_code', 50)->nullable();
			$table->string('card_number', 50)->nullable();
			$table->enum('status', [
				Gateways::TRANSACTION_INIT,
				Gateways::TRANSACTION_SUCCEED,
				Gateways::TRANSACTION_FAILED,
			])->default(Gateways::TRANSACTION_INIT);
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
