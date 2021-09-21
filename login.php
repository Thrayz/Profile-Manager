<?php
session_start();
require_once "pdo.php";
if ( isset($_POST['cancel'] ) ) {

    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123



if (isset($_POST['pass']) && isset($_POST['email'])) {
  $check = hash('md5', $salt . $_POST['pass']);

  $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');

  $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));


  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row !== false) {

      $_SESSION['name'] = $row['name'];

      $_SESSION['user_id'] = $row['user_id'];

// Redirect the browser to index.php

      header("Location: index.php");

      return;
  }
}
// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "pdo.php"; ?>
<title>Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.htmlentities($_SESSION["error"])."</p>\n");
        unset($_SESSION["error"]);
}
?>
<form method="POST">
<label for="nam">Email Address</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
</p>
</div>
</body>