<?php
session_start();

require_once "pdo.php";
require_once "util.php";


if ( isset($_POST['cancel']) ) {
    header('Location: index.php');
    return;
}
if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])
    && isset($_POST['headline']) && isset($_POST['summary'])) {
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 ||
        strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: add.php");
        return;
    } elseif (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Bad Email';
    } elseif (!validatePos()) {
        $_SESSION['error'] = "All fields are required";
    } else {
        $stmt = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)');

        $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'])
        );

        $profile_id = $pdo->lastInsertId();

        $rank = 1;
        for ($i = 1; $i <= 9; $i++) {
            if (!isset($_POST['year' . $i])) continue;
            if (!isset($_POST['desc' . $i])) continue;

            $year = $_POST['year' . $i];
            $desc = $_POST['desc' . $i];

            $stmt = $pdo->prepare('INSERT INTO Position
    (profile_id, rank, year, description)
    VALUES ( :pid, :rank, :year, :desc)');

            $stmt->execute(array(
                    ':pid' => $profile_id,
                    ':rank' => $rank,
                    ':year' => $year,
                    ':desc' => $desc)
            );

            $rank++;

        }

        $rank = 1;
        for ($i = 1; $i <= 9; $i++) {
            if (!isset($_POST['edu_year' . $i])) continue;
            if (!isset($_POST['edu_school' . $i])) continue;

            $edu_year = $_POST['edu_year' . $i];
            $edu_school = $_POST['edu_school' . $i];

            $stmt = $pdo->prepare("SELECT * FROM Institution where name = :xyz");
            $stmt->execute(array(":xyz" => $edu_school));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $institution_id = $row['institution_id'];
            } else {
                $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES ( :name)');

                $stmt->execute(array(
                    ':name' => $edu_school,
                ));
                $institution_id = $pdo->lastInsertId();
            }

            $stmt = $pdo->prepare('INSERT INTO Education
    (profile_id, institution_id, year, rank)
    VALUES ( :pid, :institution, :edu_year, :rank)');


            $stmt->execute(array(
                    ':pid' => $profile_id,
                    ':institution' => $institution_id,
                    ':edu_year' => $edu_year,
                    ':rank' => $rank)
            );

            $rank++;

        }

        $_SESSION['success'] = "Record added.";
        header("Location: index.php");
        return;
    }
} ?>
<!DOCTYPE html>
<style>
    body {
   background-color: #FFFFFF;
top : 20%;
background-position: left top -100px;
background-repeat: no-repeat;
background-attachment: fixed;
    background-image : url('home.png') ;
    

text-align : center ;
}
form {
    display: inline-block;
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
</style>    
<html>
<head>
    <?php require_once "head.php"; ?>
    <title>Add Page</title>
</head>
<body>
<div class="container">
    <h1>Add a profile </h1>
    <?php
// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
?>
    <form method="post" class="form" >
        <div class="wrapper">
        <p>First Name:
            <input type="text" name="first_name" size="60" class="first-in"/></p>
        <p>Last Name:
            <input type="text" name="last_name" size="60" class="first-in"/></p>
        <p>Email: 
            <input type="text" name="email" size="60" class="first-in"/></p>
        <p>Headline:
            <input type="text" name="headline" size="60" class="first-in"/></p>
        </div>    
        <p>Summary:<br/>
            <textarea name="summary" rows="8" cols="80"></textarea>
        <p>
            Education: <input type="submit" id="addEdu" value="+">
        <div id="edu_fields">
        </div>
        </p>
        <p>
            Position: <input type="submit" id="addPos" value="+">
        <div id="position_fields">
        </div>
        </p>
        <p>
            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
    <script>
        countPos = 0;
        countEdu = 0;

        
        $(document).ready(function () {
            window.console && console.log('Document ready called');

            $('#addPos').click(function (event) {
                
                event.preventDefault();
                if (countPos >= 9) {
                    alert("Maximum of nine position entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("Adding position " + countPos);
                $('#position_fields').append(
                    '<div id="position' + countPos + '"> \
            <p>Year: <input type="text" name="year' + countPos + '" value="" /> \
            <input type="button" value="-" onclick="$(\'#position' + countPos + '\').remove();return false;"><br>\
            <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea>\
            </div>');
            });

            $('#addEdu').click(function (event) {
                event.preventDefault();
                if (countEdu >= 9) {
                    alert("Maximum of nine education entries exceeded");
                    return;
                }
                countEdu++;
                window.console && console.log("Adding education " + countEdu);

                $('#edu_fields').append(
                    '<div id="edu' + countEdu + '"> \
            <p>Year: <input type="text" name="edu_year' + countEdu + '" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu' + countEdu + '\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="edu_school' + countEdu + '" class="school" value="" />\
            </p></div>'
                );

                $('.school').autocomplete({
                    source: "school.php"
                });

            });

        });

    </script>
</div>
</body>
</html>