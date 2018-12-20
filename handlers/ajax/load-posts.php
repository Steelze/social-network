<?php
require_once '../require.php';

use app\Input;
use app\Model\Post;
use Carbon\Carbon;
use app\Redirect;

if (Input::exists()) {
    $posts = new Post();
    $data = $posts->timeline(Input::get('start'), Input::get('limit'));
    
    ob_start();
    foreach($data as $post):
    ?>
        <div class="post">
            <div class="user-block">
                <img class="img-bordered-sm rounded-circle" src="<?= $post->avatar ?>" alt="<?= $post->fname ?>">
                    <span class="username">
                        <a href="<?= $post->username ?>"><?= $post->fname ?></a>
                    </span>
                <span class="description"><?= Carbon::parse($post->updated_at)->diffForHumans() ?></span>
            </div>
            <!-- /.user-block -->
            <div class="activitytimeline">
                <p>
                    <?= $post->body ?>
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
    <?php   
    endforeach;
    echo ob_get_clean();     
}
Redirect::to('index');
?>