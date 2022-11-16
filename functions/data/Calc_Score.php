<?php
$userID = $_SESSION["id"];
// Get all list id's from last weeks 
$result = $link->query("SELECT id FROM user_list WHERE created_at > now() - INTERVAL 7 day AND user_id = $userID");

// Count how many list's are made the last week
$lists = mysqli_fetch_array($link->query("SELECT count(id) FROM user_list WHERE created_at > now() - INTERVAL 7 day AND user_id = $userID"));

//variables 
$Alles_Score = null;
$Aantal_lijsten = null;
$Max_Points = null;
$Min_Points = null;

if ($lists[0] > 0) {

  //For each list check if all questions are answered
  foreach ($result as $result) {
    $user_list_id = $result["id"];

    $questions_count = mysqli_fetch_array($link->query("SELECT count(*) FROM `question`"));
    $answers_count =  mysqli_fetch_array($link->query("SELECT count(*) FROM `user_answer` WHERE `user_list_id` = $user_list_id"));

    $r = $link->query("SELECT `id` FROM `question`");
    // Calculate how much point's are possible to get
    foreach ($r as $r) {
      $Question_id = $r["id"];
      $points = mysqli_fetch_array($link->query("SELECT MAX(`points`) FROM `option` WHERE `question_id` = $Question_id"));
      $Max_Points += $points[0];
      $points2 = mysqli_fetch_array($link->query("SELECT MIN(`points`) FROM `option` WHERE `question_id` = $Question_id"));
      $Min_Points += abs($points2[0]);
      $total_Points = $Max_Points + $Min_Points;
    }

    // if all questions are answered in a list display Gauge
    if ($questions_count == $answers_count) {
      $Aantal_lijsten += 1;
      $score = mysqli_fetch_array($link->query("SELECT SUM(`option`.`points`) FROM `user_answer` INNER JOIN `option` ON `user_answer`.`option_id` = `option`.`id` WHERE `user_list_id` = $user_list_id"));

      $Alles_Score +=  ($total_Points / 2) + $score[0];
    }
  }
  if ($Aantal_lijsten > 0) {
    // If there is a list with all questions answered
    echo "createGauge('Test', 'profile-gauge', $Alles_Score, $total_Points);";
  } else { // If there is a list but not all questions are answered
    echo "createGauge('Test', 'profile-gauge', 50, 100);";
  }
}
// if there is no list's made yet
else {
  echo "createGauge('Test', 'profile-gauge', 50, 100);";
}