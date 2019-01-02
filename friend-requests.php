<?php
require_once 'init.php';

use app\Config;
use app\Session;
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
$collections = $user->pendingRequests();
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
                                <?php
                                    $collection = (int) $collection->user_id;
                                    $data = new User($collection) 
                                ?>
                                <div class="col-md-12 col-lg-3">
                                    <div class="box box-default">
                                        <img class="card-img-top img-responsive" src="<?= $data->getUser()->avatar ?>" alt="Card image cap">
                                        <div class="box-body text-center">            	
                                            <h4 class="box-title"><?= $data->getFullName() ?></h4>
                                            <form action="<?= Router::route('handlers.friend.accept-friend') ?>" method="post" class="text-center my-2">
                                                <input type="hidden" name="id" value="<?= $data->getUser()->id ?>">
                                                <input type="hidden" name="token" value="<?= Token::getToken() ?>">
                                                <input type="submit" value="Accept" name="accept" class="btn btn-primary btn-sm">
                                            </form>                                   
                                            <form action="<?= Router::route('handlers.friend.ignore-friend') ?>" method="post" class="text-center my-2">
                                                <input type="hidden" name="id" value="<?= $data->getUser()->id ?>">
                                                <input type="hidden" name="token" value="<?= Token::getToken() ?>">
                                                <input type="submit" value="Ignore" name="ignore" class="btn btn-primary btn-sm">
                                            </form>                                   
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                <!-- /.box -->
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php else: ?>
                        <h4 class="login-box-msg">No pending requests</h4>
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