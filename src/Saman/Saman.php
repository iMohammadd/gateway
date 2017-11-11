<?php

namespace Aries\Gateway\Saman;

use Aries\LaravelSetting\Facade\Setting;
use Illuminate\Support\Facades\Input;
use SoapClient;
use Aries\Gateway\PortAbstract;
use Aries\Gateway\PortInterface;

class Saman extends PortAbstract implements PortInterface
{
    /**
     * Address of main SOAP server
     *
     * @var string
     */
    protected $serverUrl = 'https://sep.shaparak.ir/payments/referencepayment.asmx?wsdl';

    /**
     * {@inheritdoc}
     */
    public function set($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function ready()
    {
        $this->newTransaction();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function redirect()
    {

        return view('gateway::saman-redirector')->with([
            'amount' => $this->amount,
            'merchant' => Setting::get('saman-merchant'),
            'resNum' => $this->transactionId(),
            'callBackUrl' => $this->getCallback()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function verify($transaction)
    {
        parent::verify($transaction);

        $this->userPayment();
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
            $this->callbackUrl = Setting::get('saman-cb-url', '/');

        $url = $this->makeCallback($this->callbackUrl, ['transaction_id' => $this->transactionId()]);

        return $url;
    }


    /**
     * Check user payment
     *
     * @return bool
     *
     * @throws SamanException
     */
    protected function userPayment()
    {
        $this->refId = Input::get('RefNum');
        $this->trackingCode = Input::get('ResNum');
        $payRequestRes = Input::get('State');
        $payRequestResCode = Input::get('StateCode');

        if ($payRequestRes == 'OK') {
            return true;
        }

        $this->transactionFailed();
        $this->newLog($payRequestResCode, @SamanException::$errors[$payRequestRes]);
        throw new SamanException($payRequestRes);
    }


    /**
     * Verify user payment from bank server
     *
     * @return bool
     *
     * @throws SamanException
     * @throws SoapFault
     */
    protected function verifyPayment()
    {
        $fields = array(
            "merchantID" => Setting::get('saman-merchant'),
            "RefNum" => $this->refId,
            "password" => Setting::get('saman-password'),
        );


        try {
            $soap = new SoapClient($this->serverUrl);
            $response = $soap->VerifyTransaction($fields["RefNum"], $fields["merchantID"]);

        } catch (\SoapFault $e) {
            $this->transactionFailed();
            $this->newLog('SoapFault', $e->getMessage());
            throw $e;
        }

        $response = intval($response);

        if ($response != $this->amount) {

            //Reverse Transaction
            if($response>0){
                try {
                    $soap = new SoapClient($this->serverUrl);
                    $response = $soap->ReverseTransaction($fields["RefNum"], $fields["merchantID"], $fields["password"], $response);

                } catch (\SoapFault $e) {
                    $this->transactionFailed();
                    $this->newLog('SoapFault', $e->getMessage());
                    throw $e;
                }
            }

            //
            $this->transactionFailed();
            $this->newLog($response, SamanException::$errors[$response]);
            throw new SamanException($response);
        }


        $this->transactionSucceed();

        return true;
    }


}