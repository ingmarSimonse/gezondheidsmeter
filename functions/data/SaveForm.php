<?php

global $link;

if (!isset($_GET["user_list_id"])) {
    die("Error: user_list_id not found");
}
$user_list_id = $_GET["user_list_id"];
$userID = $_SESSION["id"];

// delete all previous answers
$link->query("DELETE FROM user_answer WHERE user_list_id = $user_list_id");

// insert answers
foreach ($_POST as $item) {
    $link->query("INSERT INTO user_answer (user_list_id, option_id) VALUES ($user_list_id, $item)");
}

$link->close();

header("location: ./");

