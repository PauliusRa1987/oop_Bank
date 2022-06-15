<?php

namespace Bankas;

class Converter{
    
    public static function convert(){
    $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://openexchangerates.org/api/latest.json?app_id=9b4c0999ecec42b1babe25dc073e7e95');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $output = curl_exec($ch);
      $output = json_decode($output);
      $eur = round($output->rates->EUR, 3);
      return $eur ;
    }
}
// https://api.currencyfreaks.com/latest?apikey=106c3e5224d0483592f869cd529d77ae
// https://openexchangerates.org/api/latest.json?app_id=9b4c0999ecec42b1babe25dc073e7e95