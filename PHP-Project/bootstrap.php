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
        $timeDifference = time() - $dateTime;
    
        if( $timeDifference < 1 )
        {
            return 'less than 1 second ago';
        }
    
        $timeFormat = array( 
                    12 * 30 * 24 * 60 * 60  =>  'year',
                    30 * 24 * 60 * 60       =>  'month',
                    24 * 60 * 60            =>  'day',
                    60 * 60                 =>  'hour',
                    60                      =>  'minute',
                    1                       =>  'second'
        );
    
        foreach( $timeFormat as $secs => $str )
        {
            $timeAmount = $timeDifference / $secs;
    
            if( $timeAmount >= 1 )
            {
                $totalTimeAmount = round( $timeAmount );
                return 'about ' . $totalTimeAmount . ' ' . $str . ( $totalTimeAmount > 1 ? 's' : '' ) . ' ago';
            }
        }
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