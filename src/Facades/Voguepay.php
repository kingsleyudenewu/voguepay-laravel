<?php
/**
 * Created by PhpStorm.
 * User: Kingsley
 * Date: 15/06/2018
 * Time: 4:46 PM
 */

namespace Kingsley\Voguepay\Facades;

use Illuminate\Support\Facades\Facade;

class Voguepay extends Facade
{
    /**
     * Get the registered name of the component
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'voguepay-laravel';
    }
}