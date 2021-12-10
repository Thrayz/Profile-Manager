<?php // Do not put any HTML above this line
session_start();
require_once "pdo.php";

if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing autos_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM Position where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$rowsofpos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM Education join Institution on Education.institution_id = Institution.institution_id where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$rowsofedu = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<style>
body {
  
  top : 20%;
  background-position: left top -100px;
  background-repeat: no-repeat;
  background-attachment: fixed;
      background-image : url('home.png') ;
      
  
  
  text-align : center ;
  }


.container {
    color: white;
  mix-blend-mode: difference;
  font: 9 4vmin/5vh cookie, cursive;
  text-align: center
}
</style>
<head>
    <?php require_once "bootstrap.php"; ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
    <title> Login Page</title>
</head>
<body>
<div class="container">
  
    <h1>Profile information</h1>
    <h3>First Name: <?php echo($row['first_name']); ?></h3>
    <h3>Last Name: <?php echo($row['last_name']); ?></h3>
    <h3>Email: <?php echo($row['email']); ?></h3>
    <h3>Headline:<br/> <?php echo($row['headline']); ?></h3>
    <h3>Summary: <br/><?php echo($row['summary']); ?></h3>
    <h3>Education: <br/><ul>
        <?php
        foreach ($rowsofedu as $row) {
            echo('<li>'.$row['year'].':'.$row['name'].'</li>');
        } ?>
    </ul></h3>
    <h3>Position: <br/><ul>
        <?php
        foreach ($rowsofpos as $row) {
            echo('<li>'.$row['year'].':'.$row['description'].'</li>');
        } ?>
        </ul></h3>
    <a href="index.php">Done</a>
</div>
</body>
</html>