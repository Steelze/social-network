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
$posts = new Post();
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
                                <div class="col-4 text-center"><i class="fa fa-heart"></i> <a href="#" id="like-count" class="link"><?= $user->likesCount() ?></a></div>
                                <div class="col-4 text-center"><a href="#" class="link"><i class="fa fa-newspaper"></i> <?= $user->postsCount() ?></a></div>
                                <div class="col-4 text-center"><a href="#" class="link"><i class="fa fa-user"></i> <?= $user->friendsCount() ?></a></div>
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
                                <!-- upload -->
                                <div class="post">
                                    <div class="user-block float-left">
                                        <img class="img-bordered-sm rounded-circle" src="<?= Auth::user()->avatar ?>" alt="<?= Auth::user()->fname ?>">
                                    </div>
                                    <!-- /.user-block -->
                                    <div class="activitytimeline">
                                        <form class="form-element" action="<?= Router::route('handlers.index.save-post') ?>" method="post">
                                            <textarea class="form-control input-sm" name="post" rows="3" placeholder="What's on your mind?"></textarea>
                                            <input type="hidden" name="token" value="<?= Token::getToken() ?>">
                                            <input type="submit" name="new-post" class="btn btn-danger pull-right btn-sm" value="Post">
                                        </form>
                                    </div>
                                </div>
                                <!-- /.upload -->
                                <!-- Post -->
                                <div class="post-wrapper">
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
    <script>
        $(document).ready(function() {
            let limit = 2;
            let start = 0;
            more = true;
            function loadMorePosts(start, limit) {
                $.post({
                    url: "<?= Router::route('handlers.ajax.load-posts')?>",
                    data: {limit, start},
                    cache: false,
                    success(data) {
                        if (data === '') {
                            more = false;
                            $('.post-wrapper').append('<p class="text-center">No posts available</p>');
                        } else {
                            $('.post-wrapper').append(data);
                            // console.log(data);
                            more = true;
                        }
                    }
                })
            }
            loadMorePosts(start, limit);
            // https://stackoverflow.com/questions/3898130/check-if-a-user-has-scrolled-to-the-bottom
            $(window).scroll(function() {
                if(($(window).scrollTop() + $(window).height() == $(document).height()) && more) {
                    more = false;
                    start += limit;
                    loadMorePosts(start, limit);
                }
            });
            //
        });
        function commentBox(id) {
            const element = document.getElementById(`comment-box${id}`);
            if (element.style.display == "block") {
                element.style.display = "none";
            } else {
                element.style.display = "block";
            }
        }
        // <i class="fa fa-thumbs-up margin-r-5">
        function likePost(element, id) {
            let count = $(`#like-count${id}`).text();
            let total_count = $('#like-count').text();
            if (element.classList.contains('text-primary')) {
                element.classList.remove('text-primary')
                $(`#like-count${id}`).text(parseInt(count) - 1);
                $('#like-count').text(parseInt(total_count) - 1);
                action = 'unlike';
            } else {
                element.className += ' text-primary';
                $(`#like-count${id}`).text(parseInt(count) + 1);
                $('#like-count').text(parseInt(total_count) + 1);
                action = 'like';
            }
            $.post({
                url: "<?= Router::route('handlers.ajax.like-posts')?>",
                data: {token: "<?= Token::getToken()?>", 'action': action, id},
                cache: false,
                success(data) {
                    // console.log(data);
                },
                error(e) {
                    console.log(e  + 'error');
                }
            })
        }
    </script>
</body>