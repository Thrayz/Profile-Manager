<?php
session_start();
require_once "pdo.php";
require_once "util.php";
if ( isset($_POST['cancel'] ) ) {

    header("Location: first.php");
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



      header("Location: index.php");

      return;
  }
  else {
    echo('<p style="color: red;">' . "Wrong credentials" . "</p>\n");
        unset($_SESSION['error']);
  }
}

?>
<!DOCTYPE html>
<html>
  <style>
body {
  background-color: #FFFFFF;
top : 20%;
background-position: left top -100px;
background-repeat: no-repeat;

    background-image : url('home.png') ;
    

text-align : center ;
}

.container {


text-align : center ;
}

.container1 {
  position : relative;
  width: 500px;
  clear: both;
  left : 30%;
  padding : 25px;
}

.container1 input {
  width: 100%;
  clear: both;
  text-align : center ;
}

input[type=submit] {
  background-color: #04AA6D;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer; }
  
  input[type=text], select, textarea {
  width: 100%; 
  padding: 12px; 
  border: 1px solid #ccc; 
  border-radius: 4px; 
  box-sizing: border-box; 
  margin-top: 6px; 
  margin-bottom: 16px; 
  resize: vertical 
}

input[type=password] {
  width: 100%; 
  padding: 12px;
  border: 1px solid #ccc; 
  border-radius: 4px; 
  box-sizing: border-box; 
  margin-top: 6px; 
  margin-bottom: 16px; 
  resize: vertical 
}

label {
  font-family: "Times New Roman", Times, serif;
}
</style>
<head>
<?php require_once "pdo.php"; ?>
<title>Login Page</title>
</head>
<body>
<div class="container">
<h1 style=" font-family: "Lucida Console", "Courier New", monospace;">Log In</h1>
<?php
if ( isset($_SESSION["error"]) ) {
        echo('<p style="color:red">'.htmlentities($_SESSION["error"])."</p>\n");
        unset($_SESSION["error"]);
}
?>
<div class="container1">
<form method="POST">
<label for="nam">Email : </label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password : </label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In"> <br>

</br>
<input type="submit" name="cancel" value="Cancel">
</form>
</div>

</div>
</body>