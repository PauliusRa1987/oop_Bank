<?php

namespace Bankas\Controllers;
use Bankas\Messages as M;
use Bankas\App;
use Bankas\Safe;
use Bankas\Validations as V;




class CreateController
{

    public function toCreatePage()
    {
        if (!LogController::isLogged()) {
                      App::redirect('login');
                  }
        $id = Safe::clientId();
        $iban = CreateController::acountNr();
        return App::view('create', ['title' => 'Bankas', 'id' => $id, 'iban' => $iban, 'messages' => M::get()]);
    }

    public static function acountNr()
    {
        $controlNumber = '';
        $bankCode = '23456';
        for ($i = 0; $i < 2; $i++) {
            $controlNumber .= rand(0, 9);
        }
        $uniqNumber = '';
        for ($j = 0; $j < 11; $j++) {
            $uniqNumber .= rand(0, 9);
        }

        return 'LT' . $controlNumber . $bankCode . $uniqNumber;
    }
    
    public function keep()
    {
        if (!LogController::isLogged()) {
            App::redirect('login');
        }
        if(
            !empty($_POST)
            && V::nameValid($_POST['name'])
            && V::nameValid($_POST['surname'])
            && V::idValid($_POST['personId'])
        ){ 
        $acount = [];
        $acount = ['client' => ($_POST['client'] ?? 0),
        'sasNr' => ($_POST['code'] ?? 0), 
        'name' => ($_POST['name'] ?? 0), 
        'surname' => ($_POST['surname'] ?? 0), 
        'personId' => ($_POST['personId'] ?? 0), 
        'suma' => 0];
        Safe::get()->create($acount);
        M::add('Naujas vartotojas pridėtas į sistemą', 'success');
        return App::redirect('accounts');
        }
        
        return App::redirect('create');   
    }


    public function keepJson($data)
    {
        
        if(
            !empty($data)
            && V::nameValid($data['name'])
            && V::nameValid($data['surname'])
            && V::idValid($data['personId'])
        ){ 
        $acount = [];
        $acount = ['client' => Safe::clientId(),
        'sasNr' => CreateController::acountNr(), 
        'name' => ($data['name'] ?? 0), 
        'surname' => ($data['surname'] ?? 0), 
        'personId' => ($data['personId'] ?? 0), 
        'suma' => 0];
        Safe::get()->create($acount);
        
        $msg ='Naujas vartotojas pridėtas į sistemą';
        $style = 'good';
        return App::json(['msg'=> $msg, 'style' => $style]);
        }
        $msg = 'Neteisingai įvesti duomenys arba toks vartotojas jau egzistuoja';
        $style = 'bad';
        return App::json(['msg'=> $msg, 'style' => $style]); 
    }
}
