<?php
if (isset($_GET["GetForm"])) {
    echo GetForm(null);
}

function GetForm($user_list_id) {
    global $link;

    if (!isset($user_list_id)) {
        // check if user_list for today exists
        $userID = $_SESSION["id"];
        $current_form = array();
        $result = $link->query("SELECT id FROM user_list
        WHERE CAST(created_at AS DATE) = CAST(CURDATE() AS DATE) AND user_id = $userID");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array()) {
                array_push($current_form, $row);
            }
        }

        if (!empty($current_form)) {
            $user_list_id = $current_form[0]["id"];
        } else {
            // create new user_list
            $link->query("INSERT INTO user_list (user_id, total_points, active) VALUES ($userID, 0, 1)");

            $result = $link->query("SELECT LAST_INSERT_ID()");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
                    $user_list_id = $row["LAST_INSERT_ID()"];
                }
            }
        }
    }

    //get category
    $result = $link->query("SELECT `id`, `name` FROM `category`");
    $formArray = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            array_push($formArray, $row);
        }
    }

    // get created_at
    $result = $link->query("SELECT DATE(created_at) FROM user_list WHERE id = $user_list_id");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            $created_at = $row["DATE(created_at)"];
        }
    }

    for ($i = 0; $i < count($formArray); $i++) {
        // get questions
        $formArray[$i]["question"] = array();
        $category_id = $formArray[$i]["id"];
        $result = $link->query("SELECT `id`, `name`, `category_id` FROM `question` WHERE `category_id` = $category_id");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array()) {
                array_push($formArray[$i]["question"], $row);
            }
        }

        // save user_list_id
        $formArray[$i]["user_list_id"] = $user_list_id;
        // save created_at
        $formArray[$i]["created_at"] = $created_at;

        for ($o = 0; $o < count($formArray[$i]["question"]); $o++) {
            // get options
            $formArray[$i]["question"][$o]["option"] = array();
            $question_id = $formArray[$i]["question"][$o]["id"];
            $result = $link->query("SELECT `id`, `name`, `points`, `question_id` FROM `option` 
                                            WHERE `question_id` = $question_id");
            if ($result->num_rows > 0) {
                $u = 0;
                while ($row = $result->fetch_array()) {
                    array_push($formArray[$i]["question"][$o]["option"], $row);
                    $optionID = $row["id"];
                    $formArray[$i]["question"][$o]["option"][$u]["selected"] = "";

                    // get answers from filled form
                    if (isset($user_list_id)) {
                        $user_result = $link->query("SELECT `option_id` FROM `user_answer` 
                                WHERE `user_list_id` = $user_list_id AND `option_id` = $optionID");
                        if ($user_result->num_rows > 0) {
                            while ($user_result->fetch_array()) {
                                $formArray[$i]["question"][$o]["option"][$u]["selected"] = "checked";
                            }
                        }
                    }
                    $u++;
                }
            }
        }
    }

    $link->close();

    return $formArray;
}