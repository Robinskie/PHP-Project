<?php
        require_once "bootstrap.php";
        //$photo = new Photo;
        
        $result = Db::simpleFetchAll('SELECT id, longitude, latitude FROM photos');
        //var_dump($result);

        echo json_encode($result);
