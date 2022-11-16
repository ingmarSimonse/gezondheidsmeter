<?php
/* this function creates user_lists between the latest filled user_list
  and today that haven't been created yet */
function CreateUncreatedForms() {
    global $link;

    // get latest filled user_list date
    $userID = $_SESSION["id"];
    $lastFormDate = null;
    $result = $link->query("SELECT DATE(created_at) FROM user_list WHERE user_id = $userID ORDER BY created_at DESC LIMIT 1");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $lastFormDate = date_create($row["DATE(created_at)"]);
        }
    }

    if ($lastFormDate != null) {
        date_add($lastFormDate, date_interval_create_from_date_string("1 day"));

        // insert new user_lists
        while ($lastFormDate <  date_sub(date_create(), date_interval_create_from_date_string("1 day"))) {
            $insertDate = date_format($lastFormDate,"Y-m-d H:i:s");
            $link->query("INSERT INTO user_list (user_id, total_points, active, created_at) VALUES 
                        ($userID, 0, 1, '$insertDate')");
            date_add($lastFormDate, date_interval_create_from_date_string("1 day"));
        }
    }
}