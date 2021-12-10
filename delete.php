<?php
session_start();

if ( isset($_POST['cancel'] ) ) {

    header("Location: index.php");
    return;
}

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

require_once "pdo.php";

if ( isset($_POST['Delete']) && isset($_POST['profile_id']) ) {
    
    $sql = "DELETE FROM Profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['profile_id']) ) {
    $_SESSION['error'] = "Missing user_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT first_name, last_name FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile id';
    header('Location: index.php');
    return;
}
?>

<!DOCTYPE html>

<html>
    <style>

.container {
  position : relative;
  width: 500px;
  clear: both;
  left : 30%;
  padding : 25px;
 
}

    
input[type=submit] {
  background-color: #04AA6D;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer; }

  body {
  
top : 20%;
background-position: left top -100px;
background-repeat: no-repeat;
background-attachment: fixed;
    background-image : url('home.png') ;
    


text-align : center ;
}

h3 {
    color: white;
  mix-blend-mode: difference;
  font: 9 4vmin/5vh cookie, cursive;
  text-align: center;
}
    </style>
<head>
    <title>Welcome to the Profile Manager</title>
    
</head>
<body>
<div class="container">
    <h2>Are you sure you want to delete this profile ?</h2>
    <h3>First Name: <?php echo($row['first_name']); ?></h3>
    <h3>Last Name: <?php echo($row['last_name']); ?></h3>
    <form method="post"><input type="hidden" name="profile_id" value="<?php echo $_GET['profile_id'] ?>">
        <input type="submit" name="Delete" value="Delete">
        <input type="submit" name="cancel" value="cancel">
    </form>
</div>
</body>