<?php

require_once '../bootstrap.php';
/*
$a[] = 'Giraf';
$a[] = 'Axolotl';
$a[] = 'Plompe Lori';
$a[] = 'Sheep';
*/

$search = new Search();
$nameArray = $search->searchNames();

    $q = $_REQUEST['q'];

    $hint = '';
        if ($q !== '') {
            $q = strtolower($q);
            $len = strlen($q);
            foreach ($nameArray as $names) {
                foreach ($names as $name) {
                    if (stristr($q, substr($name, 0, $len))) {
                        if ($hint === '') {
                            $hint = $name;
                        } else {
                            $hint .= ", $name";
                        }
                    }
                }
            }
        }

        echo $hint === '' ? 'no suggestion' : $hint;
