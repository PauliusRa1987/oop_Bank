<?php
namespace Bankas\Controllers;
use Bankas\App;
use Bankas\Safe;
use Bankas\Messages as M;

class LogController{
    public $data;

    public function showLogin()
    {
        return App::view('login', ['messages' => M::get(), 'title' => 'Bankas', 'csrf' => App::csrf()]);
    }
    public function login()
    {
        if(($_POST['csrf'] ?? '') != App::csrf()) {
            M::add('Blogas kodas', 'alert');
            return App::redirect('login');
        }
        if(!empty($_POST['username']) && !empty($_POST['password'])){ 
        $user = $_POST['username'] ?? ''; 
        $pass = md5($_POST['password']) ?? '';
        $data = App::$db->showUs();
        foreach ($data as $us) {
        if ( $user == $us['username'] && $pass == $us['password']) {
            $_SESSION['login'] = 1;
            $_SESSION['username'] = $user;
            App::redirect('');}
        }
        }else{ 
            M::add('Klaidingi duomenys!', 'alert');
            App::redirect('login');
        }
    
    }
    public function logout()
    {
        unset($_SESSION['login'], $_SESSION['username']);
        M::add('Jus esate Atjungtas :)', 'success');
        App::redirect('login');
    }
    public static function isLogged() : bool
    {
        return isset($_SESSION['login']) && $_SESSION['login'] == 1;
    }
   
}