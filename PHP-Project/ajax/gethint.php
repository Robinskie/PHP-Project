<?php

// index.php rest van code

require_once '../bootstrap.php';

$search = new Search();
// een functie in Search.Class.php
$nameArray = $search->searchNames();

    $q = $_REQUEST['q'];

    $hint = '';
        if ($q !== '') {
            $q = strtolower($q);
            $len = strlen($q);
            foreach ($nameArray as $names) {
                foreach ($names as $name) {
                    if (stristr($q, substr($name, 0, $len))) {
                        // link
                        if ($hint === '') {
                            $hint = '<a id="linkHint" href="search.php?search='.$name.'">'.$name.'</a>';
                        } else {
                            $hint .= ', '.'<a id="linkHint" href="search.php?search='.$name.'">'.$name.'</a>';
                        }
                    }
                }
            }
        }

        echo $hint === '' ? 'no suggestion' : $hint;
