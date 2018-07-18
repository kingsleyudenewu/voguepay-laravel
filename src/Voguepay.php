<?php

/**
 * Created by PhpStorm.
 * User: apple
 * Date: 15/06/2018
 * Time: 4:27 PM
 */

namespace Kingsley\Voguepay;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Exception;

class Voguepay
{
    /**
     * The preferred method for server-server connections
     * @var connection_type;
     */
    protected $connection_type;
    
    /**
     * Instance of Client from GuzzleHttp
     * @var Client
     */
    protected $client;


    /**
     * Voguepay API base Url
     * Required
     * @var string
     */
    protected $baseUrl;

    /**
     * Voguepay Merchant_id
     * Required
     * @var string
     */
    protected $v_merchant_id;

    /**
     * Voguepay developer_code
     * Optional
     * @var string
     */
    protected $developer_code;

    /**
     * Voguepay Merchant currency
     * Required
     * @var string
     *
     *
     * NGN - Nigerian Naira
    USD - US Dollar
    EUR - Euro
    GBP - British Pound
    ZAR - South African Rand
     *
     *
     * This field is optional. If not provided, transaction will be initiated and processed in default currency of your VoguePay account.
     * In situations when a currency code is provided, and the provided country code differs from the VoguePay account currency. VoguePay      * conversion rates will be used for settlements.
     *
     * Please take note
     */
    protected $cur;

    /**
     * Voguepay merchant notification url
     * Optional
     * @var string
     */
    protected $notify_url;

    /**
     * Voguepay Merchant success url
     * Optional
     * @var string
     */
    protected $success_url;

    /**
     * Voguepay Merchant fail url
     * Optional
     * @var string
     */
    protected $fail_url;

    /*
     * Create a contructor here
     */
    public function __construct()
    {
        //Load the header request by default
        $this->setRequestOptions();
        $this->setVMerchantId();
        $this->setCur();
        $this->setDeveloperCode();
        $this->setNotifyUrl();
        $this->setSuccessUrl();
        $this->setFailUrl();
        $this->setBaseUrl();
        $this->setConnectionType();
    }

    /**
     * Set options for making the Client request
     */
    private function setRequestOptions()
    {
        $this->client = new Client(
            [
                'base_uri' => $this->baseUrl,
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json'
                ]
            ]
        );
    }
    
    /**
     * @param string $v_merchant_id
     */
    public function setConnectionType()
    {
        $this->connection_type = 'curl';
    }

    /**
     * @param string $v_merchant_id
     */
    public function setMerchantId()
    {
        $this->v_merchant_id = Config::get('voguepay.v_merchant_id');
    }

    /**
     * @param string $cur
     */
    public function setCur()
    {
        $this->cur = Config::get('voguepay.cur');
    }

    /**
     * @param string $developer_code
     */
    public function setDeveloperCode()
    {
        $this->developer_code = Config::get('voguepay.developer_code');
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl()
    {
        $this->baseUrl = Config::get('voguepay.paymentUrl');
    }

    /**
     * @param string $notify_url
     */
    public function setNotifyUrl()
    {
        $this->notify_url = Config::get('voguepay.notify_url');
    }

    /**
     * @param string $success_url
     */
    public function setSuccessUrl()
    {
        $this->success_url = Config::get('voguepay.success_url');
    }

    /**
     * @param string $fail_url
     */
    public function setFailUrl()
    {
        $this->fail_url = Config::get('voguepay.fail_url');
    }

    /**
     * @param $transactionData
     *
     * @return array|string
     */
    public function serializeItemsToJson($transactionData)
    {
        $items = [];
        foreach ($transactionData as $key => $value) {
            if (strpos($key, 'item_') === 0) {
                $items[substr($key, 5)]['item'] = $value;
            }
            if (strpos($key, 'price_') === 0) {
                $items[substr($key, 6)]['price'] = $value;
            }
            if (strpos($key, 'description_') === 0) {
                $items[substr($key, 12)]['description'] = $value;
            }
        }
        if (empty($items)) {
            $items = json_encode([
                1 => [
                    'item'        => $transactionData['memo'],
                    'price'       => $transactionData['total'],
                    'description' => isset($transactionData['description'])
                        ? $transactionData['description']
                        : 'Billed Every '.$transactionData['interval'].' days',
                ],
            ]);
            return $items;
        }
        $items = json_encode($items);
        return $items;
    }

    /**
     * @param string $merchant_ref
     * @param array  $transactionData
     * @param string $class
     * @param string $buttonTitle
     *
     * Render Pay Button For Particular Product
     *
     * @throws UnknownPaymentGatewayException
     *
     * @return string
     */
    public function payButton($transactionData = [], $class = '', $buttonTitle = 'Pay Now', $defaultButton)
    {
        return $this->generateSubmitButtonForVoguePay($transactionData, $class, $buttonTitle, $defaultButton);
    }

    /**
     * @param $merchantRef
     * @param array  $transactionData
     * @param string $class
     * @param string $buttonTitle
     *
     * @throws Exception
     *
     * @return string
     */
    private function generateSubmitButtonForVoguePay($transactionData, $class, $buttonTitle, $defaultButton=null)
    {

        $allowedFields = ['merchant_ref', 'v_merchant_id', 'cur', 'notify_url', 'success_url', 'fail_url', 'developer_code', 'memo', 'developer_code', 'store_id', 'total', 'recurrent', 'name', 'address', 'city', 'phone', 'email', 'zipcode', 'state'];

        $transactionData = $this->extractNeededTransactionData($transactionData, $allowedFields);

        $voguePayButtons = [
            'buynow_blue.png', 'buynow_red.png', 'buynow_green.png', 'buynow_grey.png', 'addtocart_blue.png',
            'addtocart_red.png', 'addtocart_green.png', 'addtocart_grey.png', 'checkout_blue.png',
            'checkout_red.png', 'checkout_green.png', 'checkout_grey.png', 'donate_blue.png', 'donate_red.png',
            'donate_green.png', 'donate_grey.png', 'subscribe_blue.png', 'subscribe_red.png',
            'subscribe_green.png', 'subscribe_grey.png', 'make_payment_blue.png', 'make_payment_red.png',
            'make_payment_green.png', 'make_payment_grey.png',
        ];


        $formId = 'payform';
        $hiddens = [];
        $configs = [];
        $addition = [];

        foreach ($transactionData as $key => $val) {
            $hiddens[] = '<input type="hidden" name="'.$key.'" value="'.$val.'" />'."\n";
        }



        if ((isset($transactionData['total']) or array_key_exists('price_1', $transactionData)) === false) {
            throw new Exception("Please enter a price for your product");
        }
        //$merchantRef = '<input type="hidden" name="merchant_ref" value="'.$merchantRef.'" />'."\n";
        //$defaultButton = $this->getConfig('voguepay', 'submitButton');
        $addition[] = in_array($defaultButton, $voguePayButtons)
            ? '<input type="image"  src="https://voguepay.com/images/buttons/'.$defaultButton.'" alt="Submit">'
            : '<input type="submit"  class="'.$class.'">'.$buttonTitle.'</input>';


        $form = '<script src="https://voguepay.com/js/voguepay.js"></script>';
        $form .= '<form onsubmit="return false;" method="POST" action="'.$this->baseUrl.'" id="'.$formId.'">'.
            implode('', $configs).implode('', $hiddens).implode('', $addition).
            '</form>';
        $form .= "<script>
                    Voguepay.init({form:'payform'});
                </script>";

        return $form;
    }

    /**
     * @param $transactionData
     * @param $allowedFields
     *
     * @return mixed
     */
    private function extractNeededTransactionData($transactionData, $allowedFields)
    {
        $redefinedTransactionData = [];
        $generalFields = ['price_', 'description_', 'item_'];
        foreach ($transactionData as $key => $data) {
            if (in_array($key, $allowedFields)) {
                $redefinedTransactionData[$key] = $data;
            }
            if (starts_with($key, $generalFields)) {
                $redefinedTransactionData[$key] = $data;
            }
        }
        return $redefinedTransactionData;
    }
    
    private function getPaymentDetails($transaction_id,$type="json")
    {
        //currently only json format is supported. But XML would be added soon.
        $url = "https://voguepay.com/?v_transaction_id={$transaction_id}&type={$type}";
        if($this->connection_type =="curl")
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            if($this->proxy)
            {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
                //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            }
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windowos NT 5.1; en-NG; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13 Vyren Media-VoguePay API Ver 1.0");
            if(curl_errno($ch)){ curl_error($ch)." - [Called In getPaymentDetails() CURL]"; }
            $output = curl_exec($ch);
            curl_close($ch);
        }
        if($this->connection_type =="fgc")
        {
            $output = file_get_contents($url);
            if(!$output) {$output = "Failed To Get JSON Data - [Called In getPaymentDetails() FGC]"; }
        }
        return $output;
    }
    
    public function verifyPayment($transaction_id)
    {
        $details = json_decode($this->getPaymentDetails($transaction_id,"json"));
        if(!$details){ return json_encode(array("state"=>"error","msg"=>"Failed Getting Transaction Details - [Called In verifyPayment()]"));}
        if($details->total < 1) return json_encode(array("state"=>"error","msg"=>"Invalid Transaction"));
        if($details->status != 'Approved') return json_encode(array("state"=>"error","msg"=>"Transaction {$details->status}"));
        return json_encode(array("state"=>"success","msg"=>"Transaction Approved"));
    }









}
