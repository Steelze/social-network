<?php
require_once 'init.php';

use app\Input;
use app\Redirect;
use app\Auth\Auth;
use app\Layouts;
use app\Model\User;
use app\Token;
use app\Router;
use app\Model\Post;
use Carbon\Carbon;


if (!Auth::check()) {
    Redirect::to('register');
}
$user = new User();
if (Input::exists('get')) {
    if (Input::get('q') !== '') {
        $collections = $user->searchAll(Input::get('q'));
    } else {
        $collections = [];
    }
} else {
    $collections = [];
}
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

                            <h3 class="profile-username text-center"><a  href="<?= Auth::user()->username ?>"><?= $user->getFullName() ?></a></h3>
                            <div class="row social-states">
                                <div class="col-4 text-center"><i class="fa fa-heart"></i> <a href="javascript:void(0)" id="like-count" class="link"><?= $user->likesCount() ?></a></div>
                                <div class="col-4 text-center"><a href="javascript:void(0)" class="link"><i class="fa fa-newspaper"></i> <?= $user->postsCount() ?></a></div>
                                <div class="col-4 text-center"><a href="javascript:void(0)" class="link"><i class="fa fa-user"></i> <?= $user->friendsCount() ?></a></div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <div class="col-xl-9 col-lg-9">
                    <?php if(count($collections)): ?>
                        <div class="row">
                            <?php foreach($collections as $collection): ?>
                                <div class="col-md-12 col-lg-3">
                                    <div class="box box-default">
                                        <img class="card-img-top img-responsive" src="<?= $collection->avatar ?>" alt="Card image cap">
                                        <div class="box-body text-center">            	
                                            <h4 class="box-title"><a href="<?= $collection->username ?>"><?= $collection->fname ?> <?= $collection->lname ?></a></h4>
                                            <?php if(!$user->isFriend($collection->id)): ?>
                                                <form action="<?= Router::route('handlers.friend.add-friend') ?>" method="post" class="text-center my-2">
                                                    <input type="hidden" name="id" value="<?= $collection->id ?>">
                                                    <input type="hidden" name="username" value="<?= $collection->username ?>">
                                                    <input type="hidden" name="token" value="<?= Token::getToken() ?>">
                                                    <input type="submit" value="Add Friend" name="add" class="btn btn-primary btn-sm">
                                                </form>
                                            <?php endif ?>
                                        </div>
                                        <p class="text-center"><?= $collection->mutual ?> Mutual Friends</p>
                                        <!-- /.box-body -->
                                    </div>
                                <!-- /.box -->
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php else: ?>
                        <h4 class="login-box-msg">No user found</h4>
                    <?php endif ?>
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