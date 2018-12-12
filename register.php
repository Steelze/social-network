<?php
    require_once 'init.php';
    
    use app\DB;

    $db =DB::getInstance();
    var_dump($db->select('users', [])->get());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
    <form action="register.php" method="post">
        <input type="text" name="reg_fname" placeholder="First Name">
        <br>
        <input type="text" name="reg_lname" placeholder="Last Name">
        <br>
        <input type="text" name="reg_email" placeholder="Email">
        <br>
        <input type="password" name="reg_password" placeholder="Password">
        <br>
        <input type="password" name="reg_password" placeholder="Confirm Password">
        <br>
        <input type="submit" name="reg_button" placeholder="Register">
    </form>
</body>
</html>