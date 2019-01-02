<?php
require_once 'init.php';

use app\Redirect;
use app\Auth\Auth;
use app\Layouts;
use app\Model\User;
use app\Token;
use app\Input;
use app\Router;
use app\Model\Post;
use Carbon\Carbon;
use app\Model\Notification;


if (!Auth::check()) {
    Redirect::to('register');
}

if (Input::exists('get')) {
    if (Input::get('id') === '') {
        Redirect::to('index');
    }
} else {
    Redirect::to('index');
}
$posts = new Post();
if (!$posts->check(Input::get('id'))) {
    Redirect::to(404);
}
$notification = new Notification();
$notification->opened(Input::get('id'));
$post = $posts->getSingle(Input::get('id'));
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
                                    <div class="post">
                                        <div class="user-block">
                                            <img class="img-bordered-sm rounded-circle" src="<?= $post->avatar ?>" alt="<?= $post->fname ?>">
                                                <span class="username">
                                                    <a href="<?= $post->username ?>">
                                                        <?= $post->fname ?>
                                                    </a>
                                                    <?php if($post->recepient): ?>
                                                    <small><i class="fa fa-arrow-right"></i></small>
                                                    <a href="<?= $post->recepient->username ?>">
                                                        <?= $post->recepient->fname ?>
                                                    </a>
                                                    <?php endif ?>
                                                </span>
                                            <span class="description"><?= Carbon::parse($post->updated_at)->diffForHumans() ?></span>
                                        </div>
                                        <!-- /.user-block -->
                                        <div class="activitytimeline">
                                            <p>
                                                <?= $post->body ?>
                                            </p>
                                            <ul class="list-inline">
                                                <li><button type="button" class="link-black text-sm no-pad btn btn-outline"><i class="fa fa-share margin-r-5"></i> Share</button></li>
                                                <li><button type="button" class="btn btn-outline link-black text-sm no-pad <?= ($post->hasLiked) ? 'text-primary' : '' ?>" onclick="likePost(this, <?= $post->id ?>)"><i class="fa fa-thumbs-up margin-r-5"></i></button>
                                                <span id="like-count<?= $post->id ?>"><?= $post->like ?></span>
                                                </li>
                                                <li class="pull-right">
                                                <button type="button" onclick="commentBox(<?= $post->id ?>)" class="link-black text-sm btn btn-outline no-pad"><i class="fa fa-comments margin-r-5"></i> (<?= $post->comment ?>)</button></li>
                                                <?php if(Auth::user()->id === $post->user_id): ?>
                                                    <li class="pull-right">
                                                    <button type="button" onclick="deletePost(<?= $post->id ?>)" class="link-black text-sm btn btn-outline no-pad"><i class="fa fa-trash margin-r-5"></i></button></li>
                                                <?php endif ?>
                                            </ul>
                                            <div id="comment-box<?= $post->id ?>" style="display: block;">
                                                <form autocomplete="off" class="form-element" action="<?= Router::route('handlers.index.save-comment') ?>" method="post">
                                                    <input class="form-control input-sm" name="comment" type="text" placeholder="Type a comment">
                                                    <input type="hidden" name="token" value="<?= Token::getToken() ?>">
                                                    <input type="hidden" name="post-id" value="<?= $post->id ?>">
                                                    <input type="submit" name="new-comment" class="btn btn-danger pull-right btn-sm" value="Comment">
                                                </form>
                                                <hr>
                                                <?php
                                                    foreach($post->comments as $comment):
                                                    ?>
                                                    <div class="post">
                                                        <div class="user-block">
                                                            <img class="img-bordered-sm rounded-circle" src="<?= $comment->avatar ?>" alt="<?= $comment->fname ?>">
                                                                <span class="username">
                                                                    <a href="<?= $comment->username ?>"><?= $comment->fname ?></a>
                                                                </span>
                                                            <span class="description"><?= Carbon::parse($comment->updated_at)->diffForHumans() ?></span>
                                                        </div>
                                                        <!-- /.user-block -->
                                                        <div class="activitytimeline">
                                                            <p>
                                                                <?= $comment->comment ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <?php   
                                                    endforeach;
                                                ?>
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
    <script>
        function deletePost(id) {
            $.post({
                url: "<?= Router::route('handlers.ajax.delete-post')?>",
                data: {token: "<?= Token::getToken()?>", id},
                cache: false,
                success(data) {
                    // console.log(data);
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