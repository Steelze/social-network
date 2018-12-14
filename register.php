<?php
    require_once 'init.php';
    use app\Input;
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
        <input type="text" name="fname" placeholder="First Name" value="<?= Input::old('fname') ?>">
        <br>
        <input type="text" name="lname" placeholder="Last Name" value="<?= Input::old('lname') ?>">
        <br>
        <input type="text" name="username" placeholder="Username" value="<?= Input::old('username') ?>">
        <br>
        <input type="email" name="email" placeholder="Email" value="<?= Input::old('email') ?>">
        <br>
        <input type="password" name="password" placeholder="Password">
        <br>
        <input type="password" name="password_confirmation" placeholder="Confirm Password">
        <br>
        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>