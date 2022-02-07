<?php
namespace App\helper;
use App\Wallet;
use App\Transaction;

    class UUIDGenerate{
        public static function accountnumber(){
            $number=mt_rand(1000000000000000,9999999999999999);
            if(Wallet::where('account_number',$number)->exists()){
                self::accountnumber();
            }
            return $number;
        }
        public static function refnumber(){
            $number=mt_rand(1000000000000000,9999999999999999);
            if(Transaction::where('ref_no',$number)->exists()){
                self::accountnumber();
            }
            return $number;
        }
        public static function trxid(){
            $number=mt_rand(1000000000000000,9999999999999999);
            if(Transaction::where('trx_id',$number)->exists()){
                self::accountnumber();
            }
            return $number;
        }

    }


?>
