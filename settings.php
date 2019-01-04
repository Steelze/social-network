<?php
require_once 'init.php';

use app\Redirect;
use app\Auth\Auth;
use app\Token;
use app\Input;
use app\Layouts;
use app\Model\User;
use app\Session;
use app\Router;


if (!Auth::check()) {
    Redirect::to('register');
}

$auth = new User();

?>
<?php include_once  Layouts::includes('layouts.head') ?>
<body>
    <?php include_once  Layouts::includes('layouts.nav') ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-xl-3 col-lg-3">
                    <!-- Profile Image -->
                    <div class="box">
                        <div class="box-body box-profile">
                            <img class="profile-user-img rounded-circle img-fluid mx-auto d-block" src="<?= Auth::user()->avatar ?>" alt="<?= Auth::user()->fname ?>">

                            <h3 class="profile-username text-center"><a  href="<?= Auth::user()->username ?>"><?= $auth->getFullName() ?></a></h3>
                            <p class="text-muted text-center">Accoubts Manager Jindal Cop.</p>
                            <div class="row social-states">
                                <div class="col-4 text-center"><i class="fa fa-heart"></i> <a href="javascript:void(0)" id="like-count" class="link"><?= $auth->likesCount() ?></a></div>
                                <div class="col-4 text-center"><a href="javascript:void(0)" class="link"><i class="fa fa-newspaper"></i> <?= $auth->postsCount() ?></a></div>
                                <div class="col-4 text-center"><a href="javascript:void(0)" class="link"><i class="fa fa-user"></i> <?= $auth->friendsCount() ?></a></div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <div class="col-xl-9 col-lg-9">
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" id="activity">
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
                                <div class="post-wrapper">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">Update Profile</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <!-- form start -->
                                                <form role="form" action="<?= Router::route('handlers.auth.update-profile') ?>" method="post" class="form-element">
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <label for="fname">First Name</label>
                                                            <input type="text" class="form-control" name="fname" id="fname" value="<?= (Input::old_exist('fname')) ? Input::old('fname') : Auth::user()->fname ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="lname">Last Name</label>
                                                            <input type="text" class="form-control" name="lname" id="lname" value="<?= (Input::old_exist('lname')) ? Input::old('lname') : Auth::user()->lname ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="username">Username</label>
                                                            <input type="text" class="form-control" name="username" id="username" value="<?= (Input::old_exist('username')) ? Input::old('username') : Auth::user()->username ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="email" class="form-control" name="email" id="email" value="<?= (Input::old_exist('email')) ? Input::old('email') : Auth::user()->email ?>">
                                                            <input type="hidden" name="token" value="<?= Token::getToken() ?>">
                                                        </div>
                                                    </div>
                                                    <!-- /.box-body -->
                                                    <div class="box-footer">
                                                        <input type="submit" class="btn btn-danger" name="update-profile" value="Update Profile">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">Update Password</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <!-- form start -->
                                                <form role="form" action="<?= Router::route('handlers.auth.update-password') ?>" method="post" class="form-element">
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <label for="old_password">Old Password</label>
                                                            <input type="password" class="form-control" name="old_password" id="old_password">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="password">Password</label>
                                                            <input type="password" class="form-control" name="password" id="password">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="password_confirmation">Password Confirmation</label>
                                                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                                                        </div>
                                                        <input type="hidden" name="token" value="<?= Token::getToken() ?>">
                                                    </div>
                                                    <!-- /.box-body -->
                                                    <div class="box-footer">
                                                        <input type="submit" class="btn btn-danger" name="update-password" value="Update Password">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">Update Avatar</h3>
                                                </div>
                                                <!-- /.box-header -->
                                                <!-- form start -->
                                                <form role="form" action="<?= Router::route('handlers.auth.update-avatar') ?>" method="post" class="form-element" enctype="multipart/form-data">
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <label for="exampleInputFile">File input</label>
                                                            <input type="file" name="avatar" id="exampleInputFile">

                                                            <p class="help-block text-red">Example block-level help text here.</p>
                                                        </div>
                                                    </div>
                                                    <!-- /.box-body -->
                                                    <div class="box-footer">
                                                        <input type="submit" class="btn btn-danger" name="update-avatar" value="Submit">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.post -->                    
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <?php include_once  Layouts::includes('layouts.scripts') ?>
</body>