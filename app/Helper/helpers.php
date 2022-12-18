
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
        // Available alpha caracters
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
     * create random $any characters
     */
    if (! function_exists('existingRandomCode')) {
        function existingRandomCode($model, $request)
        {
            $code = '91MLR4';
            $row  = DB::table($model)->where(['code'=>$request->code])->first();
            if (empty($row)) {
                return $code;
            } else {
                return 'done';
                $code = randomCode();
            }
            return $code;
        }
    }


