# voguepay-laravel
<blockquote>
<p> A laravel 5 Package for Voguepay checkout button </p>
</blockquote>
[![Issues](https://img.shields.io/github/issues/kingsleyudenewu/voguepay-laravel.svg)]
<h2>Installation</h2>

<p><a href="https://php.net" rel="nofollow">PHP</a> 5.4+ or <a href="http://hhvm.com" rel="nofollow">HHVM</a> 3.3+, and <a href="https://getcomposer.org" rel="nofollow">Composer</a> are required.</p>

<p>To get the latest version</p>
<div class="highlight highlight-source-shell">
<pre>composer require kingsley/voguepay-laravel</pre>
</div>

Once Voguepay Laravel is installed, you need to register the service provider. Open up config/app.php and add the following to the <code>providers</code>.

<p>
  <blockquote>
    If you use Laravel >= 5.5 you can skip this step and go to <span style="
    font-weight: 600;
    color: red;
">Configuration</span>
  </blockquote>  
</p>

<ul>
    <li>Kingsley\Voguepay\VoguepayServiceProvider::class</li>
</ul>

<p>Also register the facade</p>
<div class="highlight highlight-source-shell">
<pre>
'aliases' => [
    ...
    'Voguepay' => Kingsley\Voguepay\Facades\Voguepay::class,
    ...
]
</pre>
</div>

<h2>Configuration</h2>
<p>You can publish your facade using this command directly</p>
<pre>
php artisan vendor:publish --provider="Kingsley\Voguepay\VoguepayServiceProvider"
</pre>
<p>A configuration-file named voguepay.php with some sensible defaults will be placed in your config directory:</p>
<div class="highlight highlight-source-shell">
<pre>
<?php 
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
?>
</pre>
</div>

<h2>Usage</h2>
<p>Open your .env file and place this config settings</p>
<pre>
    VOGUEPAY_V_MERCHANT_ID=xxxxxxxxxx
    VOGUEPAY_CURRENCY=xxxxxxxx
    VOGUEPAY_DEVELOPER_CODE=xxxxxxxx
    VOGUEPAY_PAYMENT_URL=xxxxxxxx
    VOGUEPAY_NOTIFY_URL=xxxxxxxx
    VOGUEPAY_SUCCESS_URL=xxxxxxxxxx
    VOGUEPAY_FAIL_URL=xxxxxxxxxx
</pre>

<p>Lets take a look at some sample codes below</p>
<h5>Create a Route Pay</h5>
<div class="highlight highlight-text-html-php">
<pre>
Route::post('/pay', 'PaymentController@redirectToGateway')->name('pay'); 
</pre>
</div>


<h5>Create a Controller PaymentController</h5>
<div class="highlight highlight-text-html-php">
<pre>
<?php 
  <?php
  
  namespace App\Http\Controllers;
  
  use Illuminate\Http\Request;
  
  use App\Http\Requests;
  use App\Http\Controllers\Controller;
  use Voguepay;
  
  class PaymentController extends Controller
  {
        public function redirectToGateway(){
            $transactionData['v_merchant_id'] = Config::get('voguepay.v_merchant_id');
            $transactionData['cur'] = config('voguepay.cur');
            $transactionData['paymentUrl'] = config('voguepay.paymentUrl');
            $transactionData['merchant_ref'] = uniqid(6, true);
            $transactionData['memo'] = "Sample Voguepay form";
            $transactionData['item_1'] = "Domain name";
            $transactionData['description_1'] = "Sample Domain purchase";
            $transactionData['price_1'] = 3000;
            $transactionData['item_2'] = "Domain name";
            $transactionData['description_2'] = "Sample Domain purchase";
            $transactionData['price_2'] = 5000;
            $transactionData['developer_code'] = config('voguepay.developer_code');
            $transactionData['memo'] = "Sample Voguepay form"
            $transactionData['store_id'] = 25;
            $transactionData['total'] = 8000;
            $transactionData['name'] = "Tofunmi Falade";
            $transactionData['address'] = "Oluyole bodija";
            $transactionData['phone'] = "08054327653";
            $transactionData['email'] = "tfuckvoguepay@nomail.com"
            $transactionData['notify_url'] = config('voguepay.notify_url');
            $transactionData['fail_url'] = config('voguepay.fail_url');
            $transactionData['success_url'] = config('voguepay.success_url');
            $voguepay = Voguepay::payButton($transactionData, $class = '', $buttonTitle = 'Pay Now', 'make_payment_blue.png');
            return view('voguepay', compact('voguepay')); 
        }
  }
?>
</pre>
</div>

<h2>Contribution</h2>
<p>Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.</p>

<h2>Appreciation</h2>
<p>I want to urge you to please star my repo and contribute to the payment community at large</p>
