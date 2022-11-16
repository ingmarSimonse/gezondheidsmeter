<!DOCTYPE html>
<html lang="en">
<?php

require_once "./functions/data/tableData.php";
require_once "./functions/data/CreateUncreatedForms.php";
require_once "./functions/data/getTip.php";

CreateUncreatedForms();
// countPoints();
$userList = TableData(false, null);
if (isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 1;
}

$totalpages = count($userList);
?>

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="./styles/main.css" />
  <link rel="stylesheet" href="./styles/profile.css" />
  <title>Gezondheidsmeter - Profiel</title>
</head>

<script src="./functions/gauge.js"></script>


<body>
  <section id="top">
    <header>
      <div class="bar">
        <div class="title">
          <h3 class="greenTitle">Gezondheidsmeter</h3>
        </div>
        <div class="buttons">
          <div class="notification_button">
            <a class="notification" href="#"><span class="fa fa-bell-o">
                <span class="badge">1</span></a>
            </span>
            <div class="notification_box">
              <div>
                <h4>Meldingen</h4>
              </div>
              <div class="alert_content"></div>
            </div>
          </div>
          <a href="./Logout" class="text-green">Uitloggen</a>
          <a href="./formulier" class="btn-outside">Vragenlijst</a>
        </div>
      </div>
    </header>

    <div class="container profile">
      <div class="text-area greenText">
        <h1>
          Welkom, <?php echo ($_SESSION["username"]) ?>
        </h1>
        <h6>
          TIP: <?php echo $randomTip; ?>
        </h6>
        <img src="./assets/icons-profile.png" alt="" class="icons-profile">
      </div>
      <div class="img-area">
        <div class="weekly-text">
          <div class="weekly-title">Wekelijks gemiddelde</div>
          <div class="weekly-subtitle">Gebasseerd op de afgelopen 7 dagen</div>

          <div data-maxvalue='1000' data-value='500' class='chart-gauge profile-gauge mx-auto'></div>
        </div>
      </div>
    </div>
  </section>

  <section id="green">
    <div class="container-table d-flex flex-column align-items-center p-5">
      <div class="table-title-area w-75 d-flex justify-content-between">
        <div class="title bottompd">Ingevulde vragenlijsten</div>
        <div class="search">
          <div class="form-group has-search">

          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table w-75 mx-auto text-center">
          <thead>
            <tr>
              <th scope="col">Datum</th>
              <th scope="col">Score</th>
              <th scope="col">Arbeidsomstandigheden</th>
              <th scope="col">Sport & Beweging</th>
              <th scope="col">Consumptie</th>
              <th scope="col">Drugs</th>
              <th scope="col">Slaap</th>
              <th scope="col">Wijzigen</th>
            </tr>
          </thead>
          <tbody>
            <?php


            for ($i = 0; $i < count($userList) - 1; $i++) {
              $allpoints = null;

              if ($userList[$i]["Arbeidsomstandigheden"]  or $userList[$i]["SportenBeweging"] or $userList[$i]["Drugs"] or $userList[$i]["Slaap"] or $userList[$i]["Voeding"] != null) {
                $allpoints = $allpoints  + $userList[$i]["Arbeidsomstandigheden"] + $userList[$i]["SportenBeweging"] + $userList[$i]["Voeding"]
                  +   $userList[$i]["Drugs"] + $userList[$i]["Slaap"];
              }

              echo ' <tr class="TheRowOfTable">
           
              <th scope="row" class="CreatedDatum"> ' . $userList[$i]["DATE(user_list.created_at)"] . '</th>';
              if (!isset($allpoints)) {
                echo '<td></td>';
              } else if ($allpoints <= -200) {
                echo '<td><img class src="./assets/score-bad.png" alt=""></td>';
              } else if ($allpoints <= -0) {
                echo '<td> <img class src="./assets/score-average.png" alt=""></td>';
              } else if ($allpoints  <= 100) {
                echo '<td><img class src="./assets/score-nice.png" alt=""></td>';
              } else {
                echo '<td><img class src="./assets/score-good.png" alt=""></td>';
              }

              if (!isset($userList[$i]["Arbeidsomstandigheden"])) {
                echo '<td></td>';
              } elseif ($userList[$i]["Arbeidsomstandigheden"] <= -45) {
                echo '<td><img class src="./assets/score-bad.png" alt=""></td>';
              } else if ($userList[$i]["Arbeidsomstandigheden"] <= -0) {
                echo '<td> <img class src="./assets/score-average.png" alt=""></td>';
              } else if ($userList[$i]["Arbeidsomstandigheden"]  <= 30) {
                echo '<td><img class src="./assets/score-nice.png" alt=""></td>';
              } else {
                echo '<td><img class src="./assets/score-good.png" alt=""></td>';
              }


              if (!isset($userList[$i]["SportenBeweging"])) {
                echo '<td></td>';
              } elseif ($userList[$i]["SportenBeweging"] <= -0) {
                echo '<td><img class src="./assets/score-bad.png" alt=""></td>';
              } else if ($userList[$i]["SportenBeweging"] <= 15) {
                echo '<td> <img class src="./assets/score-average.png" alt=""></td>';
              } else if ($userList[$i]["SportenBeweging"]  <= 30) {
                echo '<td><img class src="./assets/score-nice.png" alt=""></td>';
              } else {
                echo '<td><img class src="./assets/score-good.png" alt=""></td>';
              }


              if (!isset($userList[$i]["Voeding"])) {
                echo '<td></td>';
              } elseif ($userList[$i]["Voeding"] <= -60) {
                echo '<td><img class src="./assets/score-bad.png" alt=""></td>';
              } else if ($userList[$i]["Voeding"] <= 0) {
                echo '<td> <img class src="./assets/score-average.png" alt=""></td>';
              } else if ($userList[$i]["Voeding"]  <= 60) {
                echo '<td><img class src="./assets/score-nice.png" alt=""></td>';
              } else {
                echo '<td><img class src="./assets/score-good.png" alt=""></td>';
              }

              if (!isset($userList[$i]["Drugs"])) {
                echo '<td></td>';
              } else if ($userList[$i]["Drugs"] <= -80) {
                echo '<td><img class src="./assets/score-bad.png" alt=""></td>';
              } else if ($userList[$i]["Drugs"] <= -40) {
                echo '<td> <img class src="./assets/score-average.png" alt=""></td>';
              } else if ($userList[$i]["Drugs"]  <= 0) {
                echo '<td><img class src="./assets/score-nice.png" alt=""></td>';
              } else {
                echo '<td><img class src="./assets/score-good.png" alt=""></td>';
              }






              if (!isset($userList[$i]["Slaap"])) {
                echo '<td></td>';
              } elseif ($userList[$i]["Slaap"] <= -15) {
                echo '<td><img class src="./assets/score-bad.png" alt=""></td>';
              } else if ($userList[$i]["Slaap"] <= -5) {
                echo '<td> <img class src="./assets/score-average.png" alt=""></td>';
              } else if ($userList[$i]["Slaap"]  <= 5) {
                echo '<td><img class src="./assets/score-nice.png" alt=""></td>';
              } else {
                echo '<td><img class src="./assets/score-good.png" alt=""></td>';
              }






              echo '
              <td><a href="./formulier?user_list_id=';
              echo $userList[$i]["id"] . '" class="fa fa-pencil white"></td>';
            } ?>



            <?php

            echo ' </tr>'; ?>


          </tbody>

        </table>

      </div>
      <div>
        <a class="btn btn-pages <?php if ($page <= 1) {
                                  echo 'disabled';
                                } ?>" href="<?php if ($page <= 1) {
                                              echo '#';
                                            } else {
                                              echo "?page=" . ($page - 1);
                                            } ?>">
          &lt;</a>
        <?php
        for ($num = 1; $num <= $userList["totalpages"]; $num++) {
          if ($page == $num) {
            echo '<a class="btn btn-pages-current mx-2" href="?page=' . $num . '">' . $num . ' </a>';
          } else {
            echo '<a class="btn btn-pages mx-2" href = "?page=' . $num . '">' . $num . ' </a>';
          }
        }

        ?>

        <a class="btn btn-pages <?php if ($page >= $totalpages) {
                                  echo 'disabled';
                                } ?>" href="<?php if ($page >= $totalpages) {
                                              echo '#';
                                            } else {
                                              echo "?page=" . ($page + 1);
                                            } ?>">
          &gt;
        </a>
      </div>

    </div>
  </section>

  <?php
  require_once "./functions/data/Check_Filled_Form.php";
  ?>

  <script type="text/javascript" src="https://d3js.org/d3.v3.min.js"></script>
  <script>
    <?php require_once "./functions/data/Calc_Score.php"; ?>
  </script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
  </script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
  </script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
  </script>
  <script>
    document.querySelector(".notification_button").addEventListener("click", changeDiv);

    function changeDiv() {
      const element = document.querySelector('.notification_box')
      element.style.display = (element.style.display == 'block') ? 'none' : 'block';
      document.querySelector('.badge').style.display = 'none'

    }
  </script>

</body>

</html>