<?php

namespace Hamraa\Payment;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hamraa\Payment\GatewayResolver
 * @method static make(Gateways\Mellat\Mellat $param)
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
