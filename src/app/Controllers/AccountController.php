<?php

namespace Bankas\Controllers;

use Bankas\App;
use Bankas\Converter;
use Bankas\Safe;
use Bankas\Messages as M;

class AccountController
{
  //oop methodes 


  public function list()
  {
    if (!LogController::isLogged()) {
      App::redirect('login');
  }
    $users = App::$db->showAll();
    $link = 'http://' . App::DOMAN . '/';
    return App::view('accounts', ['title' => 'Bankas', 'users' => $users, 'eur' =>  Converter::convert(), 'link' => $link, 'messages' => M::get()]);
  }
 
  public function add(string $id)
  {
    if (!LogController::isLogged()) {
      App::redirect('login');
  }
    $user = App::$db->show($id);
    return App::view('add', ['title' => 'Bankas', 'users' => $user, 'messages' => M::get()]);
  }
  public function addMoney(string $id)
  {
    if (!LogController::isLogged()) {
      App::redirect('login');
  }
    $duomenys = App::$db->show($id);
    if ($duomenys['client'] == $id && $_POST['add'] > 0) {
      $duomenys['suma'] += $_POST['add'];
      App::$db->update($id, $duomenys);
      M::add('Pinigai pridėti', 'success');
      return App::redirect('accounts');
    }

    M::add('OBS! Neteisingai įvesta suma!', 'alert');
    return App::redirect('add/' . $id);
  }
  
  public function remove($id)
  {
    if (!LogController::isLogged()) {
      App::redirect('login');
  }
    $user = App::$db->show($id);
    return App::view('remove', ['title' => 'Bankas', 'users' => $user, 'messages' => M::get()]);
  }

  public function outMoney(string $id)
  {
    if (!LogController::isLogged()) {
      App::redirect('login');
  }
    $duomenys = App::$db->show($id);
    if (
      $duomenys['client'] == $id
      && $duomenys['suma'] >= $_POST['remove']
      && $_POST['remove'] >= 0
    ) {
      $duomenys['suma'] -= $_POST['remove'];
      App::$db->update($id, $duomenys);
      M::add('Pinigai nuskaičiuoti', 'success');

      return App::redirect('accounts');
    }


    M::add('OBS! Sąskaitoje yra per mažai pinigų arba neteisingai įvesta suma!', 'alert');
    return App::redirect('remove/' . $id);
  }
  
  public function deleteAccount(string $id)
  {
    if (!LogController::isLogged()) {
      App::redirect('login');
  }
    $duomenys = App::$db->show($id);
    if ($duomenys['client'] == $id && $duomenys['suma'] == 0) {
      App::$db->delete($id);
      M::add('Vartotojas pašalintas iš sistemos', 'success');
      return App::redirect('accounts');
    }
    M::add('Sąskaitos, kurioje yra pinigų ištrinti negalima!', 'alert');
    return App::redirect('accounts');
  }

  // // methodes for React

  public function acountJson()
  {

    $users = App::$db->showAll();
    // $link = 'http://'.App::DOMAN.'/';
    return App::json($users);
  }


  public function addIn(string $id, array $data)
  {
    $duomenys = App::$db->show($id);
    if ($duomenys['client'] == $id && $data['newSuma'] > 0) {
      $duomenys['suma'] += $data['newSuma'];
      App::$db->update($id, $duomenys);
      $msg ='Pinigai pridėti';
      $style = 'good';
      return App::json(['msg'=> $msg, 'style' => $style]); 
    }

    $msg = 'OBS! Neteisingai įvesta suma!';
    $style = 'bad';
    return App::json(['msg'=> $msg, 'style' => $style]); 
  }
  
  public function out(string $id, array $data)
  {
    $duomenys = App::$db->show($id);
    if (
      $duomenys['client'] == $id
      && $duomenys['suma'] >= $data['newSuma']
      && $data['newSuma'] >= 0
    ) {
      $duomenys['suma'] -= $data['newSuma'];
      App::$db->update($id, $duomenys);
      $msg ='Pinigai nuskaičiuoti';
      $style = 'good';
      return App::json(['msg'=> $msg, 'style' => $style]); 
    }
    $msg ='OBS! Sąskaitoje yra per mažai pinigų arba neteisingai įvesta suma!';
    $style = 'bad';
    return App::json(['msg'=> $msg, 'style' => $style]); 
  }

  public function deleteAcc(string $id)
  {
    $duomenys = App::$db->show($id);
    if ($duomenys['client'] == $id && $duomenys['suma'] == 0) {
      App::$db->delete($id);
      $msg ='Vartotojas pašalintas iš sistemos';
      $style = 'good';
      return App::json(['msg'=> $msg, 'style' => $style]); 
    }
    $msg ='Sąskaitos, kurioje yra pinigų ištrinti negalima!';
    $style = 'bad';
    return App::json(['msg'=> $msg, 'style' => $style]); 
  }
}
