<?php

    $tipList = array();
    $result = $link->query("SELECT `name` FROM `tip`");

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            array_push($tipList, $row['name']);
        }
    }

    $number = array_rand($tipList);
    $randomTip = $tipList[$number];

?>