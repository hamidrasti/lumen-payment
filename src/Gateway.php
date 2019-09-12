<?php

namespace Hamraa\Payment;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Hamraa\Payment\GatewayResolver
 *
 * @method static Port make(Gateways\Mellat\Mellat $port)
 * @method static Gateways\Mellat\Mellat mellat()
 * @method static Gateways\Sadad\Sadad sadad()
 * @method static Gateways\Zarinpal\Zarinpal zarinpal()
 * @method static Gateways\Parsian\Parsian parsian()
 * @method static Gateways\Pasargad\Pasargad pasargad()
 * @method static Gateways\Saman\Saman saman()
 * @method static Gateways\Paypal\Paypal paypal()
 * @method static Gateways\Asanpardakht\Asanpardakht asanpardakht()
 * @method static Gateways\Payir\Payir payir()
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
