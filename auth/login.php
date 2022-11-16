<?php

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: ./");
  exit;
}

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Check if username is empty
  if (empty(trim($_POST["username"]))) {
    $username_err = "Voer een gebruikersnaam in.";
  } else {
    $username = trim($_POST["username"]);
  }

  // Check if password is empty
  if (empty(trim($_POST["password"]))) {
    $password_err = "Voer een wachtwoord in.";
  } else {
    $password = trim($_POST["password"]);
  }

  // Validate credentials
  if (empty($username_err) && empty($password_err)) {
    // Prepare a select statement
    $sql = "SELECT id, username, password FROM user WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      // Set parameters
      $param_username = $username;

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if username exists, if yes then verify password
        if (mysqli_stmt_num_rows($stmt) == 1) {
          // Bind result variables
          mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
          if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $hashed_password)) {

              // Store data in session variables
              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;

              // Redirect user to welcome page
              header("location: ./");
            } else {
              // Password is not valid, display a generic error message
              $login_err = "Ongeldige gebruikersnaam of wachtwoord.";
            }
          }
        } else {
          // Username doesn't exist, display a generic error message
          $login_err = "Ongeldige gebruikersnaam of wachtwoord.";
        }
      } else {
        echo "Oeps! Er ging iets mis. Probeer het later nog eens.";
      }

      // Close statement
      mysqli_stmt_close($stmt);
    }
  }

  // Close connection
  mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" />
  <link rel="stylesheet" href="./styles/auth.css" />
  <title>Gezondheidsmeter - Login</title>
</head>

<body>
  <div class="wrapper">
    <form class="auth" method="post">
      <h2 sty class="signup">Inloggen</h2>
      <?php
      if (!empty($login_err)) {
        echo '<div class="is-invalid">' . $login_err . '</div>';
      }
      ?>
      <div class="form-group">
        <label>Gebruikersnaam</label>
        <input class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" type="text"
          name="username" autofocus />
        <span class="invalid-feedback"><?php echo $username_err; ?></span>
      </div>
      <div class="form-group">
        <label>Wachtwoord</label>
        <input type="password" name="password"
          class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" />
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
      </div>
      <div class="auth-text">
        <h5>
          Heb je nog geen account? <br />
          klik dan <a href="./registreren">hier</a>
        </h5>
      </div>
      <input id="button" type="submit" value="Inloggen" />
    </form>
  </div>
</body>

</html>