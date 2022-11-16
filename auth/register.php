<?php

$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


  // Validate username
  if (empty(trim($_POST["username"]))) {
    $username_err = "vul een gebruikersnaam in.";
  } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
    $username_err = "De gebruikersnaam mag alleen letters, cijfers en lage streepjes bevatten.";
  } else {
    // Prepare a select statement
    $sql = "SELECT id FROM user WHERE username = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_username);

      // Set parameters
      $param_username = trim($_POST["username"]);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        /* store result */
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
          $username_err = "De ingevoerde gebruikersnaam is al bezet.";
        } else {
          $username = trim($_POST["username"]);
        }
      } else {
        echo "Oeps! Er ging iets mis. Probeer het later nog eens.";
      }

      // Close statement
      mysqli_stmt_close($stmt);
    }
  }

  // Validate password
  if (empty(trim($_POST["password"]))) {
    $password_err = "Vul een wachtwoord in.";
  } elseif (strlen(trim($_POST["password"])) < 6) {
    $password_err = "Het wachtwoord moet minimaal 6 characters lang zijn.";
  } else {
    $password = trim($_POST["password"]);
  }

  // Validate confirm password
  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Bevestig je wachtwoord.";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($password_err) && ($password != $confirm_password)) {
      $confirm_password_err = "De wachtwoorden komen niet overeen.";
    }
  }

  // Check input errors before inserting in database
  if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

    // Prepare an insert statement
    $sql = "INSERT INTO user (username, password) VALUES (?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

      // Set parameters
      $param_username = $username;
      $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Redirect to login page
        header("location: ./Login");
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
  <title>Gezondheidsmeter - Register</title>
</head>

<body>
  <div class="wrapper">
    <form class="auth" method="post">
      <h2 sty class="signup">Registreren</h2>
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
      <div class=" form-group">
        <label>Bevestig wachtwoord</label>
        <input type="password" name="confirm_password"
          class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
      </div>
      <div class="auth-text">
        <h5>
          Heb je al een account? <br />
          log dan <a href="./Inloggen">hier</a> in
        </h5>
      </div>
      <input id="button" type="submit" value="Registreren" />
    </form>
  </div>
</body>

</html>