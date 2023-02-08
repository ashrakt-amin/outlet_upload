
<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

    /**
    * Write code on Method
    *
    * @return response()
    */
    if (! function_exists('convertYmdToMdy')) {
        function convertYmdToMdy($date)
        {
            return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
        }
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    if (! function_exists('convertMdyToYmd')) {
        function convertMdyToYmd($date)
        {
            return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
        }
    }

    /**
     * create random 6 characters
     */
    if (! function_exists('randomCode')) {
        function randomCode()
        {
        // Available alpha characters
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // generate a pin based on 1 * 3 digits + a 3 random characters
        $pin = mt_rand(100, 999)
            . $characters[rand(0, strlen($characters) - 1)]
            . $characters[rand(0, strlen($characters) - 1)]
            . $characters[rand(0, strlen($characters) - 1)];
        // shuffle the result
        $code = str_shuffle($pin);
        return $code;
        }
    }

    /**
     * if randomCode exists
     */
    if (! function_exists('uniqueRandomCode')) {
        function uniqueRandomCode($tableName)
        {
            $code = randomCode();
            $row  = DB::table($tableName)->where(['code'=>$code])->first();
            if ($row == null) {
                return $code;
            } else {
                return randomCode();
            }
        }
    }


