<?php
require_once "./functions/data/GetForm.php";

$user_list_id = null;
if (isset($_GET["user_list_id"])) {
    $user_list_id = $_GET["user_list_id"];
}

$formArray = GetForm($user_list_id);


$outputDate = $formArray[0]["created_at"];

$outputForm = "<form action='./SaveForm?user_list_id={$formArray[0]["user_list_id"]}' method='POST'>";
foreach ($formArray as $category) {

    $outputForm .= "<div class='category'> <div class='formBox head'> <h1>{$category['name']}</h1> </div>";

    foreach ($category["question"] as $question) {

        $outputForm .= "<div class='formBox'> <p>{$question["name"]}</p> <div>";

        foreach ($question["option"] as $option) {
            $outputForm .=
                "<input type='radio' value='{$option["id"]}' id='{$option["id"]}' name='radio-{$question["id"]}' 
                        {$option["selected"]}>
                <label for='{$option["id"]}'>{$option["name"]}</label>";
        }

        $outputForm .= "</div></div>";
    }

    $outputForm .= "</div>";
}

$outputForm .= "<input type='submit' value='Opslaan'/></form>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
    <link rel="stylesheet" href="./styles/main.css" />
    <link rel="stylesheet" href="./styles/form.css" />
    <title>Gezondheidsmeter - Formulier</title>
</head>

<body>
<section class="background">
    <header>
        <div class="bar">
            <div class="title">
                <h3>Gezondheidsmeter | <?=$outputDate?></h3>
            </div>
            <div class="buttons">
                <a href="./Logout">Uitloggen</a>
                <a href="./" class="btn-outside">Stoppen</a>
            </div>
        </div>
    </header>

    <?=$outputForm?>

</section>


</html>