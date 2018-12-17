<?php
require_once 'init.php';
use app\Input;
use app\Router;
use app\Session;
use app\Assets;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <script src="<?= Assets::url('js/all.min.js') ?>"></script>	
    <link rel="stylesheet" href="<?= Assets::url('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= Assets::url('css/style.css') ?>">
    <link rel="stylesheet" href="<?= Assets::url('css/master_style.css') ?>">
</head>
<body class="hold-transition login-page">
    <!-- LOGIN PAGE -->
    <div class="login-box">
        <div class="login-logo">
            <a href=""><b>MinimalLite</b>Admin</a>
        </div>
        <!-- /logo -->

        <div class="login-box-body">
            <p class="login-box-msg">
                <?php
                    if (Session::exists('errors')) {
                        foreach (Session::flash('errors') as $value) {
                            echo $value . '<br>';
                        }
                    } 

                    if (Session::exists('msg')) {
                        echo Session::flash('msg') . '<br>';
                    }
                ?>
            </p>
            <div id="login-box">
                <form action="<?= Router::route('handlers.auth.login') ?>" method="post" class="form-element">
                    <div class="form-group">
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email address" value="<?= Input::old('email') ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="row">
                        <div class="col-6">
                        <div class="checkbox">
                            <input type="checkbox" id="basic_checkbox_1" >
                            <label for="basic_checkbox_1">Remember Me</label>
                        </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-6">
                        <div class="fog-pwd">
                            <a href="javascript:void(0)"><i class="ion ion-locked"></i> Forgot pwd?</a><br>
                        </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-12 text-center">
                        <input type="submit" class="btn btn-info btn-block margin-top-10" name="login" value="SIGN IN">
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <div class="social-auth-links text-center">
                    <p>- OR -</p>
                    <a href="javascript:void(0)" class="btn btn-social-icon btn-circle btn-facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="javascript:void(0)" class="btn btn-social-icon btn-circle btn-google"><i class="fab fa-google-plus-g"></i></a>
                </div>
                <!-- /.social-auth-links -->
                <div class="margin-top-10 text-center">
                    <p>Don't have an account? <a href="#" id="register-btn" class="text-info m-l-5">Sign Up</a></p>
                </div>
            </div>
            <div id="register-box">
                <form action="<?= Router::route('handlers.auth.register') ?>" method="post" class="form-element">
                    <div class="form-group">
                        <input type="text" name="fname" class="form-control" placeholder="First Name" value="<?= Input::old('fname') ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" name="lname" class="form-control" placeholder="Last Name" value="<?= Input::old('lname') ?>">
                    </div>
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Username" value="<?= Input::old('username') ?>">
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="reg_email" class="form-control" placeholder="Email address" value="<?= Input::old('reg_email') ?>">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Password Confirmation">
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                        <input type="submit" class="btn btn-info btn-block margin-top-10" name="register" value="REGISER">
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <div class="margin-top-10 text-center">
                    <p>Already have an account? <a href="#" id="login-btn" class="text-info m-l-5">Login</a></p>
                </div>
            </div>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <script src="<?= Assets::url('js/jquery.min.js') ?>"></script>	
    <script src="<?= Assets::url('js/bootstrap.min.js') ?>"></script>
    <script>
        $(document).ready(function() {
            // On click register link show reg form
            $('#register-btn').click(function() {
                $('#register-box').show('slow');
                $('#login-box').hide('slow');
            })

            // On click login link show login form
            $('#login-btn').click(function() {
                $('#register-box').hide('slow');
                $('#login-box').show('slow');
            })
            <?php
                if (Session::exists('register')) {
                    Session::flash('register');
            ?>
                $('#register-box').show();
                $('#login-box').hide()
            <?php
                }

            ?>
        })
    </script>

</body>

</html>