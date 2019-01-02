<?php
require_once 'init.php';

use app\Redirect;
use app\Auth\Auth;
use app\Layouts;
use app\Model\User;
use Carbon\Carbon;
use app\Model\Notification;


if (!Auth::check()) {
    Redirect::to('register');
}

$notification = new Notification();
$data = $notification->getNotifications(true);
$user = new User();

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
                            <p class="text-muted text-center">Accoubts Manager Jindal Cop.</p>
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
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="tab-pane active" id="activity">
                                <div class="post-wrapper">
                                    <?php if(count($data)): ?>
                                    <?php foreach($data as $value): ?>
                                        <?php $notUser = new User($value->sender); ?>
                                        <div class="post p-2 <?= (!$value->opened) ? 'bg-secondary' : '' ?>">
                                            <div class="user-block">
                                                <img class="img-bordered-sm rounded-circle" src="<?= $notUser->getUser()->avatar ?>" alt="<?= $notUser->getUser()->fname ?>">
                                                    <span class="username">
                                                        <a href="<?= $notUser->getUser()->username ?>">
                                                            <?= $notUser->getFullName() ?>
                                                        </a>
                                                    </span>
                                                <span class="description"><?= Carbon::parse($value->created_at)->diffForHumans() ?></span>
                                            </div>
                                            <!-- /.user-block -->
                                            <div class="activitytimeline">
                                                <a href="<?= $value->link ?>">
                                                    <p>
                                                        <?= $value->message ?>
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                    <?php else: ?>
                                        <p class="text-center">No notifications</p>
                                    <?php endif ?>
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