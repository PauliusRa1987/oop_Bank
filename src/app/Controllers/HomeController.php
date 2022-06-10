<?php
namespace Bankas\Controllers;
use Bankas\App;

class HomeController{
    public function __construct()
  {
      if (!LogController::isLogged()) {
          App::redirect('login');
      }
  }
    public function index(){
        return App::view('home', ['title' => 'Bankas']);
    }
    
}