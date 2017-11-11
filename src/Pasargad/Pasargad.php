<?php

namespace Aries\Gateway\Pasargad;

use Aries\LaravelSetting\Facade\Setting;
use Illuminate\Support\Facades\Input;
use Aries\Gateway\Enum;
use SoapClient;
use Aries\Gateway\PortAbstract;
use Aries\Gateway\PortInterface;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class Pasargad extends PortAbstract implements PortInterface
{
	/**
	 * Url of parsian gateway web service
	 *
	 * @var string
	 */

	protected $checkTransactionUrl = 'https://pep.shaparak.ir/CheckTransactionResult.aspx';
	protected $verifyUrl = 'https://pep.shaparak.ir/VerifyPayment.aspx';
	protected $refundUrl = 'https://pep.shaparak.ir/doRefund.aspx';

	/**
	 * Address of gate for redirect
	 *
	 * @var string
	 */
	protected $gateUrl = 'https://pep.shaparak.ir/gateway.aspx';

	/**
	 * {@inheritdoc}
	 */
	public function set($amount)
	{
		$this->amount = intval($amount);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function ready()
	{
		$this->sendPayRequest();

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function redirect()
	{

        $processor = new RSAProcessor(Setting::get('parsargad-cert-path', storage_path('gateway/pasargad/certificate.xml')) ,RSAKeyType::XMLFile);

		$url = $this->gateUrl;
		$redirectUrl = $this->getCallback();
        $invoiceNumber = $this->transactionId();
        $amount = $this->amount;
        $terminalCode = Setting::get('pasargad-terminal');
        $merchantCode = Setting::get('pasargad-merchant');
        $timeStamp = date("Y/m/d H:i:s");
        $invoiceDate = date("Y/m/d H:i:s");
        $action = 1003;
        $data = "#". $merchantCode ."#". $terminalCode ."#". $invoiceNumber ."#". $invoiceDate ."#". $amount ."#". $redirectUrl ."#". $action ."#". $timeStamp ."#";
        $data = sha1($data,true);
        $data =  $processor->sign($data); // امضاي ديجيتال
        $sign =  base64_encode($data); // base64_encode

		return view('gateway::pasargad-redirector')->with(compact('url','redirectUrl','invoiceNumber','invoiceDate','amount','terminalCode','merchantCode','timeStamp','action','sign'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function verify($transaction)
	{
		parent::verify($transaction);

		$this->verifyPayment();

		return $this;
	}

	/**
	 * Sets callback url
	 * @param $url
	 */
	function setCallback($url)
	{
		$this->callbackUrl = $url;
		return $this;
	}

	/**
	 * Gets callback url
	 * @return string
	 */
	function getCallback()
	{
		if (!$this->callbackUrl)
			$this->callbackUrl = Setting::get('pasargad-cb-url', '/');

		return $this->callbackUrl;
	}

	/**
	 * Send pay request to parsian gateway
	 *
	 * @return bool
	 *
	 * @throws ParsianErrorException
	 */
	protected function sendPayRequest()
	{
		$this->newTransaction();
	}

	/**
	 * Verify payment
	 *
	 * @throws ParsianErrorException
	 */
	protected function verifyPayment()
	{
        $processor = new RSAProcessor(Setting::get('pasargad-cert-path', storage_path('gateway/pasargad/certificate.xml')),RSAKeyType::XMLFile);
        $fields = array('invoiceUID' => Input::get('tref'));
        $result = Parser::post2https($fields,'https://pep.shaparak.ir/CheckTransactionResult.aspx');
        $check_array = Parser::makeXMLTree($result);
        if ($check_array['resultObj']['result'] == "True") {
            $fields = array(
                'MerchantCode' => Setting::get('pasargad-merchant'),
                'TerminalCode' => Setting::get('pasargad-terminal'),
                'InvoiceNumber' => $check_array['resultObj']['invoiceNumber'],
                'InvoiceDate' => Input::get('iD'),
                'amount' => $check_array['resultObj']['amount'],
                'TimeStamp' => date("Y/m/d H:i:s"),
                'sign' => '',
                );

            $data = "#" . $fields['MerchantCode'] . "#" . $fields['TerminalCode'] . "#" . $fields['InvoiceNumber'] ."#" . $fields['InvoiceDate'] . "#" . $fields['amount'] . "#" . $fields['TimeStamp'] ."#";
            $data = sha1($data, true);
            $data = $processor->sign($data);
            $fields['sign'] = base64_encode($data);
            $result = Parser::post2https($fields,"https://pep.shaparak.ir/VerifyPayment.aspx");
            $array = Parser::makeXMLTree($result);
            if ($array['actionResult']['result'] != "True") {
                $this->newLog(-1, Enum::TRANSACTION_FAILED_TEXT);
                $this->transactionFailed();
                throw new PasargadErrorException(Enum::TRANSACTION_FAILED_TEXT, -1);
            }
            $this->refId = $check_array['resultObj']['referenceNumber'];
            $this->transactionSetRefId();
            $this->trackingCode = Input::get('tref');
            $this->transactionSucceed();
            $this->newLog(0, Enum::TRANSACTION_SUCCEED_TEXT);
        } else {
            $this->newLog(-1, Enum::TRANSACTION_FAILED_TEXT);
            $this->transactionFailed();
            throw new PasargadErrorException(Enum::TRANSACTION_FAILED_TEXT, -1);
        }
}
