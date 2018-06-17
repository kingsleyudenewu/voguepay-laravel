<?php
/**
 * Created by PhpStorm.
 * User: Kingsley
 * Date: 15/06/2018
 * Time: 4:11 PM
 *
 *
 * This file is part of laravel voguepay package
 * Created By
 * Kingsley Udenewu Chima
 * kingsley.udenewu@hotmail.com
 * kingzpacking@gmail.com
 */

return [
    /**
     * MERCHANT ID From Voguepay Dashboard
     * Required
     */
    'v_merchant_id' => getenv('VOGUEPAY_V_MERCHANT_ID'),
    /**
     * Currency From Voguepay Dashboard
     * Required
     */
    'cur' => getenv('VOGUEPAY_CURRENCY'),
    /**
     * Developer Code From Voguepay Dashboard
     * Optional
     */
    'developer_code' => getenv('VOGUEPAY_DEVELOPER_CODE'),
    /**
     * Voguepay Payment URL
     * Required
     */
    'paymentUrl' => getenv('VOGUEPAY_PAYMENT_URL'),
    /**
     * Optional notification Url From Merchant Voguepay Settings
     *
     */
    'notify_url' => getenv('VOGUEPAY_NOTIFY_URL'),
    /**
     * Optional success Url From Merchant Voguepay Settings
     *
     */
    'success_url' => getenv('VOGUEPAY_SUCCESS_URL'),
    /**
     * Optional failed Url From Merchant Voguepay Settings
     *
     */
    'fail_url' => getenv('VOGUEPAY_FAIL_URL'),
];