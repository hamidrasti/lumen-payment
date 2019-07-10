<?php

namespace Hamraa\Payment;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hamraa\Payment\GatewayResolver
 */
class Gateway extends Facade
{
	/**
	 * The name of the binding in the IoC container.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'gateway';
	}
}
