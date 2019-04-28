<?php
    session_start();

    spl_autoload_register(function($class){
            require_once(__DIR__ . DIRECTORY_SEPARATOR . "Classes" . DIRECTORY_SEPARATOR . $class . ".Class.php");
        });

    function checkIfLoggedIn() {
        if(!empty($_SESSION['userid'])) {
            return true;
        } else {
            return false;
        }
    }

    function redirectIfLoggedOut() {
        if(!checkIfLoggedIn()) {
            header('Location:login.php');
        }
    }

    function howLongAgo($dateTime) {
        return $dateTime;
    }