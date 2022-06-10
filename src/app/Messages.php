<?php
namespace Bankas;

    class Messages {

        private static $thing;
    
        public static function init() : void {
           self::$thing = $_SESSION['msg'] ?? [];
           unset($_SESSION['msg']);
        }
    
        public static function add(string $text, string $type) : void {
            $_SESSION['msg'][] = ['msg' => $text, 'type' => $type];
        }
    
        public static function get() : array {
            return self::$thing;
        }
    
    }