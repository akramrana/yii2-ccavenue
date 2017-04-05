<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ccavenue
 *
 * @author Akram Hossain <akram_cse@yahoo.com>
 */

namespace app\components;

use Yii;

class Ccavenue
{

    /**
     * @author Akram Hossain<akram_cse@yahoo.com>
     * @param type $params  checkout data 
     * @param type $type  purchase type
     * @param type $env  environment will be test or production
     * @param type $host hosting server will be live,websites or local
     * @purpose for subscription,payment
     */
    
    static function form($params, $type = 'Checkout', $env, $host)
    {

        $merchant_data = '';

        //for live
        if ($host == 'live') {
            $working_key = '***************'; //Shared by CCAVENUES
            $access_code = '***************'; //Shared by CCAVENUES
        }

        //for demo server if any
        if ($host == 'websites') {
            $working_key = '***************'; //Shared by CCAVENUES
            $access_code = '***************'; //Shared by CCAVENUES
        }

        //for local
        if ($host == 'local') {
            $working_key = '***************'; //Shared by CCAVENUES
            $access_code = '***************'; //Shared by CCAVENUES
        }

        foreach ($params as $key => $value) {
            $merchant_data.=$key . '=' . $value . '&';
        }

        if ($env == 'test') {
            $checkoutUrl = 'https://test.ccavenue.com';
        }
        elseif ($env == 'production') {
            $checkoutUrl = 'https://secure.ccavenue.com';
        }

        $encrypted_data = encrypt($merchant_data, $working_key);

        echo '<form id="2checkout" action="' . $checkoutUrl . '/transaction/transaction.do?command=initiateTransaction" method="post">';

        echo "<input type=hidden name=encRequest value=$encrypted_data>";
        echo "<input type=hidden name=access_code value=$access_code>";

        if ($type == 'auto') {
            echo '<input type="submit" value="Click here if you are not redirected automatically" /></form>';
            echo '<script type="text/javascript">document.getElementById("2checkout").submit();</script>';
        }
        else {
            echo '<input type="submit" value="' . $type . '" />';
            echo '</form>';
        }
    }

}
