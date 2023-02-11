<?php

namespace App\Helper;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;

class Helper
{
    use TraitsAuthGuardTrait;

    /**
     * Method for items conditions where column name
     */
    public static function globalFind($table, $id)
    {
        return DB::table($table)->find($id);
    }

     /**
     * @param $id
     * @return Model
     */
     public static function globalWhere($table, $columnName = null, $columnValue = null, $booleanName = null, $booleanValue = null)
     {
        return DB::table($table)
        ->where(function($q) use($columnName, $columnValue){
            $columnName == null  ?: $q
            ->where([$columnName => $columnValue]);
        })->where(function($q) use($booleanName, $booleanValue){
            $booleanName == null  ?: $q
            ->where([$booleanName => $booleanValue]);
        })->get();
     }

    /**
    * Write code on Method
    *
    * @return response()
    */
    // public static function slug(string $str): string
    // {
    //     $str = self::stripVnUnicode($str);
    //     $str = preg_replace('/[^A-Za-z0-9-]/', ' ', $str);
    //     $str = preg_replace('/ +/', ' ', $str);
    //     $str = trim($str);
    //     $str = str_replace(' ', '-', $str);
    //     $str = preg_replace('/-+/', '-', $str);
    //     $str=  preg_replace("/-$/", '', $str);
    //     return strtolower($str);
    // }

    /**
    * Write code on Method
    *
    * @return response()
    */
    // public static function stripVnUnicode(string $str): string
    // {
    //     if (!$str) {
    //         return false;
    //     }
    //     $str = strip_tags($str);
    //     $unicode = [
    //         'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
    //         'd' => 'đ',
    //         'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
    //         'i' => 'í|ì|ỉ|ĩ|ị',
    //         'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
    //         'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
    //         'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
    //         'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
    //         'D' => 'Đ',
    //         'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
    //         'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
    //         'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
    //         'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
    //         'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    //     ];
    //     foreach($unicode as $key  => $value) {
    //         $str = preg_replace("/($value)/i", $key, $str);
    //     }
    //     return $str;
    // }
}
