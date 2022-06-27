<?php

namespace Bankas;

use Bankas\Controllers\HomeController;
use Bankas\Controllers\AccountController;
use Bankas\Controllers\CreateController;
use Bankas\Controllers\LogController;
use Bankas\Messages;

require __DIR__ . '/Controllers/LogController.php';

class App
{
    const DOMAN = 'bankas.lt';
    private static $html;

    public static function start()
    {
        session_start();
        Messages::init();
        ob_start();
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        array_shift($uri);
        self::router($uri);
        self::$html = ob_get_contents();
        ob_end_clean();
    }

    public static function sent()
    {
        echo self::$html;
    }
    private static function router(array $uri)
    {


        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS');
            header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With");
            die;
        }

        //oop bank routs

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && count($uri) == 1 && $uri[0] === '') {
            return (new HomeController())->index();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && count($uri) == 1 && $uri[0] === 'accounts') {

            return (new AccountController())->list();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && count($uri) == 1 && $uri[0] === 'create') {
            return (new CreateController())->toCreatePage();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($uri) == 1 && $uri[0] === 'create') {
            return (new CreateController())->keep();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && count($uri) == 2 && $uri[0] === 'add') {
            return (new AccountController())->add($uri[1]);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($uri) == 2 && $uri[0] === 'add') {
            return (new AccountController())->addMoney($uri[1]);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && count($uri) == 2 && $uri[0] === 'remove') {
            return (new AccountController())->remove($uri[1]);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($uri) == 2 && $uri[0] === 'remove') {
            return (new AccountController())->outMoney($uri[1]);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($uri) == 2 && $uri[0] === 'delete') {
            return (new AccountController())->deleteAccount($uri[1]);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && count($uri) == 1 && $uri[0] === 'logout') {
            return (new LogController())->logout();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'GET' && count($uri) == 1 && $uri[0] === 'login') {
            return (new LogController())->showLogin();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($uri) == 1 && $uri[0] === 'login') {
            return (new LogController())->login();
        }

        //React routs 
       


        if ($_SERVER['REQUEST_METHOD'] == 'GET' && count($uri) == 1 && $uri[0] === 'loginAuth') {


            if (App::getUser()) {
                return self::json(['user' => 'ok']);
            } else {
                $msg = 'Klaidingi prisijungimo duomenys';
                $style = 'bad';
                return self::json(['msg' => $msg, 'style' => $style]);
            }
        }


        if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($uri) == 1 && $uri[0] === 'loginApp') {
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, 1);
            $db = new LogController;
            $users = $db->showUs();


            if ($users['username'] == $data['name'] && $users['password'] == $data['pass']) {
                $token = md5(time() . rand(0, 10000));
                $users['session'] = $token;
                file_put_contents(__DIR__ . '/server/worker.json', json_encode($users));
                return self::json(['token' => $token]);
                die;
            }
            return self::json(['msg' => 'error']);
        }

        if (App::getUser()) {
            
       
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && count($uri) == 1 && $uri[0] === 'listJson') {

            return (new AccountController())->acountJson();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($uri) == 1 && $uri[0] === 'listJson') {
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, 1);
            return (new CreateController())->keepJson($data);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE' && count($uri) == 2 && $uri[0] === 'listJson') {
            return (new AccountController())->deleteAcc($uri[1]);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'PUT' && count($uri) == 2 && $uri[0] === 'listJson') {
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, 1);
            return (new AccountController())->addIn($uri[1], $data);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'PUT' && count($uri) == 2 && $uri[0] === 'listJsonRem') {
            $rawData = file_get_contents("php://input");
            $data = json_decode($rawData, 1);
            return (new AccountController())->out($uri[1], $data);
        } else {
            //  echo 'pasiklydai';
        }
    }
    }
    public static function view(string $name, array $data = [])
    {
        extract($data);
        require __DIR__ . '/../views/' . $name . '.php';
    }

    public static function redirect(string $name)
    {
        header('Location: http://' . self::DOMAN . '/' . $name);
    }

    public static function csrf()
    {
        return md5('jsaofdis6f64sdfsdk\fbs68sdf' . $_SERVER['HTTP_USER_AGENT']);
    }
    //function for react
    public static function json(array $data = [])
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: OPTIONS,GET, POST, DELETE, PUT');
        header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With");
        echo json_encode($data);
    }
    public static function getUser()
    {
        $token = apache_request_headers()['Authorization'] ?? '';
        if ($token === '') {
            return null;
        }
        $db = new LogController;
        $users = $db->showUs();
        if ($users['session'] == $token) {
            return $users;
        }

        return null;
    }
}
