<?php
// Check if user created a list yesterday
$userID = $_SESSION["id"];
$result = $link->query("SELECT id FROM user_list
        WHERE CAST(created_at AS DATE) = CAST(DATE_ADD(CURDATE(), INTERVAL -1 DAY) AS DATE) AND user_id = $userID");

$r = $link->query("SELECT id FROM `user` WHERE CAST(created_at AS DATE) = CAST(CURDATE() AS DATE) AND id = $userID");

//If no list are found

// Check if user is created today

if ($r->num_rows != 1) {
  if ($result->num_rows == 0) {
    echo "<script>";
    echo "document.querySelector('.badge').style.display = 'inherit'";
    echo "</script>";
    echo "<script>";
    echo "document.querySelector('.alert_content').textContent = 'Gisteren is er geen vragenlijst ingevuld, Vul deze alsnog in om het wekelijks gemiddelde accuraat te houden.'";
    echo "</script>";
  } else {
    // Check if all questions are answered

    while ($row = $result->fetch_array()) {
      $user_list_id = $row["id"];
    }
    $questions_count = mysqli_fetch_array($link->query("SELECT count(*) FROM `question`"));
    $answers_count =  mysqli_fetch_array($link->query("SELECT count(*) FROM `user_answer` WHERE `user_list_id` = $user_list_id"));
    if ($questions_count[0] != $answers_count[0]) {
      echo "<script>";
      echo "document.querySelector('.badge').style.display = 'inherit'";
      echo "</script>";
      echo "<script>";
      echo "document.querySelector('.alert_content').textContent = 'Niet alle vragen uit de vragenlijst zijn ingevuld, Klik op wijzigen in de tabel hieronder om het aan te passen.'";
      echo "</script>";
    } else {
      echo "<script>";
      echo "document.querySelector('.alert_content').textContent = 'Alle vragenlijsten zijn ingevuld!'";
      echo "</script>";
    }
  }
} else {
  echo "<script>";
  echo "document.querySelector('.alert_content').textContent = 'Er zijn nog geen meldingen!'";
  echo "</script>";
}