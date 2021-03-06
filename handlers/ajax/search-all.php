<?php
require_once '../require.php';

use app\Input;
use app\Token;
use app\Redirect;
use app\Router;
use app\Auth\Auth;
use app\Model\User;

if (Input::exists()) {
    $auth = new User();
    $data = $auth->searchAll(Input::get('value'), false);
    ob_start();
    if (count($data)) {
        foreach($data as $user):
        ?>
            <a href="<?= Router::route($user->username, [], false) ?>">
                <div class="media media-single">
                    <img class="avatar avatar-xl" src="<?= $user->avatar ?>" alt="<?= $user->username ?>">
                    <div class="media-body">
                        <h6><?= $user->fname.' '.$user->lname ?></h6>
                        <small class="text-dark"><?= $user->mutual ?> Mutual friends</small>
                    </div>
                </div>
            </a>
        <?php   
        endforeach;
    }     
    echo ob_get_clean(); 
} else {
    Redirect::to('index');
}
