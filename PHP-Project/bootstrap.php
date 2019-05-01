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

    function hexToRGB($hexColor)
    {
        if( preg_match( '/^#?([a-h0-9]{2})([a-h0-9]{2})([a-h0-9]{2})$/i', $hexColor, $matches ) )
        {
            return array(
                'red' => hexdec( $matches[ 1 ] ),
                'green' => hexdec( $matches[ 2 ] ),
                'blue' => hexdec( $matches[ 3 ] )
            );
        }
        else
        {
            return array( 0, 0, 0 );
        }
}