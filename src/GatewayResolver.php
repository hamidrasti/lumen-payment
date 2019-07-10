<?php

namespace Hamraa\Payment;

use Hamraa\Payment\Gateways\Parsian\Parsian;
use Hamraa\Payment\Gateways\Paypal\Paypal;
use Hamraa\Payment\Gateways\Sadad\Sadad;
use Hamraa\Payment\Gateways\Mellat\Mellat;
use Hamraa\Payment\Gateways\Pasargad\Pasargad;
use Hamraa\Payment\Gateways\Saman\Saman;
use Hamraa\Payment\Gateways\Asanpardakht\Asanpardakht;
use Hamraa\Payment\Gateways\Zarinpal\Zarinpal;
use Hamraa\Payment\Gateways\Payir\Payir;
use Hamraa\Payment\Exceptions\RetryException;
use Hamraa\Payment\Exceptions\PortNotFoundException;
use Hamraa\Payment\Exceptions\InvalidRequestException;
use Hamraa\Payment\Exceptions\NotFoundTransactionException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class GatewayResolver
{

    protected $request;

    /**
     * @var Config
     */
    public $config;

    /**
     * Keep current port driver
     *
     * @var Mellat|Pasargad|Saman|Sadad|Zarinpal|Payir|Parsian
     */
    protected $port;

    /**
     * Gateway constructor.
     * @param null $config
     * @param null $port
     * @throws PortNotFoundException
     */
    public function __construct($config = null, $port = null)
    {
        $this->config = app('config');
        $this->request = app('request');

        if ($this->config->has('gateway.timezone')) {
            date_default_timezone_set($this->config->get('gateway.timezone'));
        }

        if (!is_null($port)) $this->make($port);
    }

    /**
     * Get supported ports
     *
     * @return array
     */
    public function getSupportedPorts()
    {
        return [
            Gateways::MELLAT,
            Gateways::SADAD,
            Gateways::ZARINPAL,
            Gateways::PARSIAN,
            Gateways::PASARGAD,
            Gateways::SAMAN,
            Gateways::PAYPAL,
            Gateways::ASANPARDAKHT,
            Gateways::PAYIR
        ];
    }

    /**
     * Call methods of current driver
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws PortNotFoundException
     */
    public function __call($name, $arguments)
    {

        // calling by this way ( Gateway::mellat()->.. , Gateway::parsian()->.. )
        if (in_array(strtoupper($name), $this->getSupportedPorts())) {
            return $this->make($name);
        }

        return call_user_func_array([$this->port, $name], $arguments);
    }

    /**
     * Gets query builder from you transactions table
     * @return mixed
     */
    function getTable()
    {
        return DB::table($this->config->get('gateway.table'));
    }

    /**
     * Callback
     *
     * @return Mellat|Pasargad|Parsian|Payir|Sadad|Saman|Zarinpal ->port
     *
     * @throws InvalidRequestException
     * @throws NotFoundTransactionException
     * @throws PortNotFoundException
     * @throws RetryException
     */
    public function verify()
    {
        if (!$this->request->has('transaction_id') && !$this->request->has('iN'))
            throw new InvalidRequestException;
        if ($this->request->has('transaction_id')) {
            $id = $this->request->get('transaction_id');
        } else {
            $id = $this->request->get('iN');
        }

        $transaction = $this->getTable()->whereId($id)->first();

        if (!$transaction)
            throw new NotFoundTransactionException;

        if (in_array($transaction->status, [Gateways::TRANSACTION_SUCCEED, Gateways::TRANSACTION_FAILED]))
            throw new RetryException;

        $this->make($transaction->port);

        return $this->port->verify($transaction);
    }


    /**
     * Create new object from port class
     *
     * @param int $port
     * @return GatewayResolver
     * @throws PortNotFoundException
     */
    function make($port)
    {
        if ($port InstanceOf Mellat) {
            $name = Gateways::MELLAT;
        } elseif ($port InstanceOf Parsian) {
            $name = Gateways::PARSIAN;
        } elseif ($port InstanceOf Saman) {
            $name = Gateways::SAMAN;
        } elseif ($port InstanceOf Zarinpal) {
            $name = Gateways::ZARINPAL;
        } elseif ($port InstanceOf Sadad) {
            $name = Gateways::SADAD;
        } elseif ($port InstanceOf Asanpardakht) {
            $name = Gateways::ASANPARDAKHT;
        } elseif ($port InstanceOf Paypal) {
            $name = Gateways::PAYPAL;
        } elseif ($port InstanceOf Payir) {
            $name = Gateways::PAYIR;
        } elseif (in_array(strtoupper($port), $this->getSupportedPorts())) {
            $port = ucfirst(strtolower($port));
            $name = strtoupper($port);
            $class = __NAMESPACE__ . '\\' . $port . '\\' . $port;
            $port = new $class;
        } else
            throw new PortNotFoundException;

        $this->port = $port;
        $this->port->setConfig($this->config); // injects config
        $this->port->setPortName($name); // injects config
        $this->port->boot();

        return $this;
    }
}
