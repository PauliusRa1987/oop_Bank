<?php
namespace Bankas\Controllers;
use Bankas\App;

class HomeController{

    public function index(){
      if (!LogController::isLogged()) {
                App::redirect('login');
            }
        return App::view('home', ['title' => 'Bankas']);
    }
    
}