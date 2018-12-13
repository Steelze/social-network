<?php
    require_once 'init.php';

    use app\Input;
    use app\Validation;
    use app\Auth\Register;
    
    if (Input::exists()) {
        if (Input::get('register')) {
            $validation = new Validation();
            $validate = $validation->check(
                [
                    'fname' => [
                        'required' => true,
                    ],
                    'lname' => [
                        'required' => true,
                    ],
                    'email' => [
                        'required' => true,
                        'email' => true,
                    ],
                    'password' => [
                        'required' => true,
                        'min' => 6,
                    ],
                    'password_confirmation' => [
                        'required' => true,
                        'matches' => 'password',
                    ],
                ],
                [
                    'fname' => [
                        'required' => 'First Name is a Required Field',
                    ],
                ]
            );
            if (!$validate->passed()) {
                var_dump($validation->errors());
            } else {
                $auth = new Register();
                $auth->register(
                    [
                        'fname' => Input::get('fname'),
                        'lname' => Input::get('lname'),
                        'email' => Input::get('email'),
                        'password' => Input::raw('password'),
                    ]
                );
            }
        }
    }
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
        <input type="text" name="email" placeholder="Email" value="<?= Input::old('email') ?>">
        <br>
        <input type="password" name="password" placeholder="Password">
        <br>
        <input type="password" name="password_confirmation" placeholder="Confirm Password">
        <br>
        <input type="submit" name="register" value="Register">
    </form>
</body>
</html>