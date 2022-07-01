<?php
namespace Bankas\Controllers;
use Bankas\App;
use Bankas\Safe;
use Bankas\Messages as M;


class SignController{


    public function showSignin()
    {
        return App::view('signin', ['messages' => M::get(), 'title' => 'Bankas', 'csrf' => App::csrf()]);
    }

    public function createU()
    {   
        if(empty($_POST['username']||
        $_POST['password']||
        $_POST['pass'] )) {
            M::add('Neteisingai įvesti duomenys', 'alert');
        }
        else {
            $name = strtolower($_POST['username'] ?? 0);
            $password = md5($_POST['password'] ?? 0);
            $pass = md5($_POST['pass'] ?? 0);
            
            if ($password === $pass && !$this->uniqInput()) {
                $data=[
                    'username' => $name,
                    'password' => $password
                ];
                App::$db->createUser($data);
                M::add('Darbuotojas pridėtas', 'success');
                App::redirect('login');
            }
            else {
                M::add('Slaptažodžiai nesutampa arba toks vartotojas jau egzistuoja!', 'alert');
            }
        }
        // redirektina atgal į save
        App::redirect('signin');
    } 
    private function uniqInput()
    {
        $users = App::$db->showUs();
        foreach ($users as $us) {
            if ($us['username'] === $_POST['username']){
                return 0;
            }
            else {
                return 1;
            }
        }
        }


}