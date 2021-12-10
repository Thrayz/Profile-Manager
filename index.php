<?php 
session_start();

require_once "pdo.php";
require_once "util.php";

$stmt = $pdo->query("SELECT profile_id, first_name,last_name , headline from users join Profile on users.user_id = Profile.user_id");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<style>
body {
   background-color: #000000;
top : 20%;
background-position: left top -100px;
background-repeat: no-repeat;

    background-image : url('home.png') ;
    

text-align : center ;
}

.container {
    
    height: 100px;
    border: 1px solid black;
    font-size: 20px;
    font-weight: bold;
    justify-content : center;
    text-align: center;
   

}
.table {
    width: 100%; /* Full width */
  padding: 12px; /* Some padding */ 
  border: 1px solid #ccc; /* Gray border */
  border-radius: 4px; /* Rounded borders */
  box-sizing: border-box; /* Make sure that padding and width stays in place */
  margin-top: 6px; /* Add a top margin */
  margin-bottom: 16px; /* Bottom margin */
  resize: vertical /* Allow the user to vertically resize the textarea (not horizontally) */
}
.table1 {
  top: 10%;
  padding: 5%;
  top: 10%;
  padding: 5%;
  color: white;
  mix-blend-mode: difference;
  font: 5 4vmin/5vh cookie, cursive;
    

}
.title {
    text-align: center;
    font-family: "Times New Roman", Times, serif;
}
.logout-btn {
    position : relative;
    text-alignment: center;
    bottom : 100%;
}

a {
    background-color: #04AA6D;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

</style>
<html>
<head>
    <?php require_once "head.php"; ?>
    <title>Resume Registry</title>
</head>
<body>
    <div class="page">
<div class="container">
    <h2 class="title">Resume Registry</h2>
    <div class="lougout-btn">
    <div>
    <?php
    if (isset($_SESSION['name'])) {
        echo '<p class="logout-btn"><a href="logout.php">Logout</a></p>';
    }
    ?>
    </div>
    <?php
    flashMessage();
    ?>
<div class="table">
    <ul>
    <center class="table1">
        <?php
        if (!isset($_SESSION['name'])) {
            header("Location: login.php");
    return;
        }
        if (true) {
            if (true) {
                echo "<table border='1'>";
                echo " <thead><tr>";
                echo "<th>Name</th>";
                echo " <th>Headline</th>";
                if (isset($_SESSION['name'])) {
                    echo("<th>Action</th>");
                }
                echo " </tr></thead>";
                foreach ($rows as $row) {
                    echo "<tr><td>";
                    echo("<a href='view.php?profile_id=" . $row['profile_id'] . "' >" . $row['first_name'] ." ". $row['last_name']  . "</a>");
                    echo("</td><td>");
                    echo($row['headline']);
                    echo("</td>");
                    if (isset($_SESSION['name'])) {
                        echo("<td>");
                        echo('<a href="edit.php?profile_id=' . $row['profile_id'] . '" style=" 
                        color: white;
                        padding: 0px 0px;
                        border: none;
                        border-radius: 2px;
                        cursor: pointer;">Edit</a>/<a href="delete.php?profile_id=' . $row['profile_id'] . '" style=" 
                        color: white;
                        padding: 0px 0px;
                        border: none;
                        border-radius: 2px;
                        cursor: pointer;">Delete</a>');
                    }
                    echo("</td></tr>\n");
                }
                echo "</table>";
            } else {
                echo 'No rows found';
            }
        }
        echo '</li></ul>';
        ?> </center></div>
        </ul>
        </div>
        <p><a href="add.php">Add New Entry</a></p>
       
</div>
    </div>
</body>
</html>