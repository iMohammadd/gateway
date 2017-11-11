<?php

namespace Aries\Gateway;

use Aries\Gateway\Parsian\Parsian;
use Aries\Gateway\Sadad\Sadad;
use Aries\Gateway\Mellat\Mellat;
use Aries\Gateway\Payline\Payline;
use Aries\Gateway\Pasargad\Pasargad;
use Aries\Gateway\Saman\Saman;
use Aries\Gateway\Zarinpal\Zarinpal;
use Aries\Gateway\JahanPay\JahanPay;
use Aries\Gateway\Exceptions\RetryException;
use Aries\Gateway\Exceptions\PortNotFoundException;
use Aries\Gateway\Exceptions\InvalidRequestException;
use Aries\Gateway\Exceptions\NotFoundTransactionException;
use Aries\LaravelSetting\Facade\Setting;
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
	 * @var Mellat|Saman|Sadad|Zarinpal|Payline|JahanPay|Parsian
	 */
	protected $port;

	/**
	 * Gateway constructor.
	 * @param null $config
	 * @param null $port
	 */
	public function __construct($config = null, $port = null)
	{
		$this->config = app('config');
		$this->request = app('request');

		if (Setting::get('gateway-timezone', 'Asia/Tehran'))
			date_default_timezone_set(Setting::get('gateway-timezone', 'Asia/Tehran'));

		if (!is_null($port)) $this->make($port);
	}

	/**
	 * Get supported ports
	 *
	 * @return array
	 */
	public function getSupportedPorts()
	{
		return [Enum::MELLAT, Enum::SADAD, Enum::ZARINPAL, Enum::PAYLINE, Enum::JAHANPAY, Enum::PARSIAN, Enum::PASARGAD, Enum::SAMAN];
	}

	/**
	 * Call methods of current driver
	 *
	 * @return mixed
	 */
	public function __call($name, $arguments)
	{

		// calling by this way ( Gateway::mellat()->.. , Gateway::parsian()->.. )
		if(in_array(strtoupper($name),$this->getSupportedPorts())){
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
		return DB::table('gateway_transactions');
	}

	/**
	 * Callback
	 *
	 * @return $this->port
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
		}else {
			$id = $this->request->get('iN');
		}

		$transaction = $this->getTable()->whereId($id)->first();

		if (!$transaction)
			throw new NotFoundTransactionException;

		if (in_array($transaction->status, [Enum::TRANSACTION_SUCCEED, Enum::TRANSACTION_FAILED]))
			throw new RetryException;

		$this->make($transaction->port);

		return $this->port->verify($transaction);
	}


	/**
	 * Create new object from port class
	 *
	 * @param int $port
	 * @throws PortNotFoundException
	 */
	function make($port)
	{
		if ($port InstanceOf Mellat) {
			$name = Enum::MELLAT;
		} elseif ($port InstanceOf Parsian) {
			$name = Enum::PARSIAN;
		} elseif ($port InstanceOf Saman) {
            $name = Enum::SAMAN;
        } elseif ($port InstanceOf Payline) {
			$name = Enum::PAYLINE;
		} elseif ($port InstanceOf Zarinpal) {
			$name = Enum::ZARINPAL;
		} elseif ($port InstanceOf JahanPay) {
			$name = Enum::JAHANPAY;
		} elseif ($port InstanceOf Sadad) {
			$name = Enum::SADAD;
		} elseif(in_array(strtoupper($port),$this->getSupportedPorts())){
			$port=ucfirst(strtolower($port));
			$name=strtoupper($port);
			$class=__NAMESPACE__.'\\'.$port.'\\'.$port;
			$port=new $class;
		} else
			throw new PortNotFoundException;

		$this->port = $port;
		$this->port->setConfig($this->config); // injects config
		$this->port->setPortName($name); // injects config
		$this->port->boot();

		return $this;
	}
}
