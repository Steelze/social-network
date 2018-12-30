<?php
require_once '../require.php';

use app\Input;
use app\Token;
use app\Model\Post;
use Carbon\Carbon;
use app\Redirect;
use app\Router;
use app\Auth\Auth;
use app\Model\User;

if (Input::exists()) {
    $posts = new Post();
    $data = $posts->timeline(Input::get('id'), Input::get('start'), Input::get('limit'));
    ob_start();
    foreach($data as $post):
    ?>
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
                    <?php
                        $user = new User(Input::get('id'));
                        if(Auth::user()->id === $post->user_id || Auth::user()->id === $user->getUser()->id ): ?>
                        <li class="pull-right">
                        <button type="button" onclick="deletePost(<?= $post->id ?>)" class="link-black text-sm btn btn-outline no-pad"><i class="fa fa-trash margin-r-5"></i></button></li>
                    <?php endif ?>
                </ul>
                <div id="comment-box<?= $post->id ?>" style="display: none;">
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
    <?php   
    endforeach;
    echo ob_get_clean();     
} else {
    Redirect::to('index');
}
