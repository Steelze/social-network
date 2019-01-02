<?php
require_once '../init.php';

use app\Config;
use app\Input;
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

$user = new User(Input::get('username'));
if (!$user->getUser()) {
    Redirect::to(404);
}

$auth = new User();
$title = ucwords(Input::get('username')).' - Social Network';

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
                            <img class="profile-user-img rounded-circle img-fluid mx-auto d-block" src="<?= $user->getUser()->avatar ?>" alt="<?= $user->getUser()->fname ?>">

                            <h3 class="profile-username text-center"><a  href="<?= $user->getUser()->username ?>"><?= $user->getFullName() ?></a></h3>
                            <?php if($auth->getUser()->id !== $user->getUser()->id): ?>
                                <p class="text-muted text-center"><?= $user->mutualCount($auth->getUser()->id) ?> Mutual Friend(s)</p>
                                    <?php if($user->isFriend($auth->getUser()->id)): ?>
                                        <form action="<?= Router::route('handlers.friend.remove-friend') ?>" method="post" class="text-center my-2">
                                            <input type="hidden" name="id" value="<?= $user->getUser()->id ?>">
                                            <input type="hidden" name="username" value="<?= $user->getUser()->username ?>">
                                            <input type="hidden" name="token" value="<?= Token::getToken() ?>">
                                            <input type="submit" value="Remove Friend" name="add" class="btn btn-primary btn-sm">
                                        </form>
                                    <?php else: ?>
                                        <?php if($user->hasReceivedRequest($auth->getUser()->id)): ?>
                                            <p class="text-muted text-center">Waiting for confirmation</p>
                                        <?php elseif($user->hasSentRequest($auth->getUser()->id)): ?>
                                            <p class="text-muted text-center">Accept</p>
                                        <?php else: ?>
                                            <form action="<?= Router::route('handlers.friend.add-friend') ?>" method="post" class="text-center my-2">
                                                <input type="hidden" name="id" value="<?= $user->getUser()->id ?>">
                                                <input type="hidden" name="username" value="<?= $user->getUser()->username ?>">
                                                <input type="hidden" name="token" value="<?= Token::getToken() ?>">
                                                <input type="submit" value="Add Friend" name="add" class="btn btn-primary btn-sm">
                                            </form>
                                        <?php endif ?>
                                    <?php endif ?>
                            <?php endif ?>
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
                                <!-- upload -->
                                <div class="post">
                                    <p class="login-box-msg" id="loading"></p>
                                    <div class="activitytimeline">
                                        <form class="form-element" action="<?= Router::route('handlers.ajax.write-post') ?>" method="post" id="post-timeline">
                                            <textarea class="form-control input-sm" name="post" id="post" rows="3" placeholder="Write..."></textarea>
                                            <input type="hidden" name="id" value="<?= $user->getUser()->id ?>">
                                            <input type="hidden" name="username" value="<?= $user->getUser()->username ?>">
                                            <input type="hidden" name="token" value="<?= Token::getToken() ?>">
                                            <input type="submit" name="new-post" class="btn btn-primary btn-sm" value="Post">
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
            //Write on timeline
            const form = $('#post-timeline');
            form.submit(function (e) {
                e.preventDefault();
                if ($('#post-timeline #post').val().trim() === '') {
                    return false;
                }
                $('#loading').text('');
                $('#loading').text('Please wait...');
                $.post({
                    url: "<?= Router::route('handlers.ajax.write-post')?>",
                    data: form.serialize(),
                    cache: false,
                    success(data) {
                        $('#loading').text('');
                        $('#post-timeline #post').val('');
                        // $(".post-wrapper").load(location.href + " .post-wrapper");
                        location.reload();
                    },
                    error(e) {
                        $('#loading').text(e.responseText);
                    }
                })
            })
            //Load Posts
            let limit = 2;
            let start = 0;
            more = true;
            function loadMorePosts(start, limit) {
                let id = <?= $user->getUser()->id ?>;
                $.post({
                    url: "<?= Router::route('handlers.ajax.load-timeline')?>",
                    data: {id, limit, start},
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

        function deletePost(id) {
            let user_id = <?= $user->getUser()->id ?>;
            $.post({
                url: "<?= Router::route('handlers.ajax.delete-timeline')?>",
                data: {token: "<?= Token::getToken()?>", id, user_id},
                cache: false,
                success(data) {
                    console.log('data');
                    location.reload();
                    // // $(".content").load(location.href + " .content");
                },
                error(e) {
                    console.log(e  + 'error');
                }
            })
        }

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