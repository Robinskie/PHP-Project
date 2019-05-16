<?php
        require_once "bootstrap.php";
        
        $result = Db::simpleFetchAll('SELECT id, longitude, latitude FROM photos');

        echo json_encode($result);
