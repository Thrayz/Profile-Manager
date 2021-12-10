<?php
session_start();

require_once "pdo.php";
require_once "util.php";
$salt = 'XyZzy12*_';


if ( isset($_POST['cancel']) ) {
    header('Location: first.php');
    return;
}


if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    
 if (strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1) {
            $_SESSION['error'] = 'All fields are required';
            header("Location: add.php");
            return;
        } elseif (strpos($_POST['email'], '@') === false) {
            $_SESSION['error'] = 'Bad Email';
        } 
        else {
            $password = hash('md5', $salt . $_POST['password']);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:na, :em, :pa)');
    
            $stmt->execute(array(
                    ':na' => $_POST['name'],
                    ':em' => $_POST['email'],
                    ':pa' => $password )
                    
            );
       $_SESSION['success'] = "Account Created";
        header("Location: index.php");
        return; }

        

    


} ?>



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

</style>
<html>
<head>
    <?php require_once "head.php"; ?>
    <title>New Account</title>
</head>
<body>
<div class="container">
    <h1>Create An Account </h1>
    <?php

if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
?>
    <form method="post" class="form" >
        <div class="wrapper">
        <p>Name:
            <input type="text" name="name" size="60" class="first-in"/></p>
        <p>email:
            <input type="text" name="email" size="60" class="first-in"/></p>
        <p>Password: 
            <input type="password" name="password" size="60" class="first-in"/></p>
        
        
        <p>
            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
    </div>
</body>
</html>