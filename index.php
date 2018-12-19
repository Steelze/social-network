<?php
require_once 'init.php';

use app\Config;
use app\Session;
use app\Redirect;
use app\Auth\Auth;
use app\Layouts;
use app\Model\User;


if (!Auth::check()) {
    Redirect::to('register');
}
$user = new User();
$title = 'Social Network';
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
                                <div class="col-6 text-right"><a href="#" class="link"><i class="fa fa-heart"></i> 254</a></div>
                                <div class="col-6 text-left"><a href="#" class="link"><i class="fa fa-user"></i> 54</a></div>
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
                                <!-- upload -->
                                <div class="post">
                                    <div class="user-block float-left">
                                        <img class="img-bordered-sm rounded-circle" src="<?= Auth::user()->avatar ?>" alt="<?= Auth::user()->fname ?>">
                                    </div>
                                    <!-- /.user-block -->
                                    <div class="activitytimeline">
                                        <form class="form-element">
                                            <textarea class="form-control input-sm" rows="4" placeholder="What's on your mind?"></textarea>
                                            <button type="submit" class="btn btn-danger pull-right btn-sm">Send</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- /.upload -->
                                <!-- Post -->
                                <div class="post">
                                    <div class="user-block">
                                        <img class="img-bordered-sm rounded-circle" src="<?= Auth::user()->avatar ?>" alt="<?= Auth::user()->fname ?>">
                                            <span class="username">
                                            <a href="#"><?= Auth::user()->fname ?></a>
                                            <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                                            </span>
                                        <span class="description">5 minutes ago</span>
                                    </div>
                                    <!-- /.user-block -->
                                    <div class="activitytimeline">
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum.
                                        </p>
                                        <ul class="list-inline">
                                            <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                                            <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-up margin-r-5"></i> Like</a>
                                            </li>
                                            <li class="pull-right">
                                            <a href="#" class="link-black text-sm"><i class="fa fa-comments margin-r-5"></i> Comments
                                                (5)</a></li>
                                        </ul>
                                        <form class="form-element">
                                            <input class="form-control input-sm" type="text" placeholder="Type a comment">
                                        </form>
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