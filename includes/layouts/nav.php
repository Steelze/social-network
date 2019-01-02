<?php
    use app\Router;
    use app\Auth\Auth;
    use app\Token;
    use app\Model\Message;
    use app\Model\User;
    use app\Model\Notification;
    use Carbon\Carbon;
    $user = new User();
    $message = new Message();
    $notifications = new Notification();
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="<?= Router::route('index') ?>">Social</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <ul class="navbar-nav" style="margin-right: 20px;">
            <li class="nav-item active">
                <a class="nav-link" href="<?= Router::route('index') ?>"><i class="fa fa-home"></i><span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown" id="drop">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-envelope"></i>
                    <?php if ($message->unreadCount()): ?>
                        <span class="badge badge-pill badge-danger drop" style="font-size: 9px;"><?= $message->unreadCount() ?></span>
                    <?php endif ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown" >
                    <div class="media-list media-list-hover media-list-divided">
                        <?php foreach($message->navConvos() as $key => $value): ?>
                            <?php $convoUser = new User($key); ?>
                            <a href="<?= Router::route('messages', ['u' => $convoUser->getUser()->username]) ?>">
                                <div class="media media-single <?= (!$value['opened'] && ($value['recepient'] === Auth::user()->id)) ? 'bg-secondary' : '' ?>">
                                    <img class="avatar" src="<?= $convoUser->getUser()->avatar ?>" alt="..." width="49px" height="49px">
                                    <div class="media-body">
                                        <small><?= $convoUser->getUser()->fname ?></small>
                                        <p><?= (strlen($value['msg']) < 20) ? $value['msg'] : substr($value['msg'], 0, 10).'...' ?></p>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                        <?php endforeach ?>
                    </div>
                    <a class="dropdown-item text-center" href="<?= Router::route('messages') ?>">See all</a>
                </div>
            </li>
            <li class="nav-item dropdown" id="notif">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    <?php if ($notifications->unreadCount()): ?>
                        <span class="badge badge-pill badge-danger notif" style="font-size: 9px;"><?= $notifications->unreadCount() ?></span>
                    <?php endif ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown" >
                    <div class="media-list media-list-hover media-list-divided">
                        <?php foreach($notifications->getNotifications() as $key): ?>
                            <a href="<?= $key->link ?>">
                                <div class="media media-single <?= (!$key->opened) ? 'bg-secondary' : '' ?>">
                                    <div class="media-body">
                                        <small><?= $key->message ?></small>
                                        <small><b> - <?= Carbon::parse($key->created_at)->diffForHumans() ?></b></small>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                        <?php endforeach ?>
                    </div>
                    <a class="dropdown-item text-center" href="<?= Router::route('notifications') ?>">See all</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= Router::route('friend-requests') ?>"><i class="fa fa-users"></i>
                <?php if ($user->frienRequestCount()): ?>
                    <span class="badge badge-pill badge-danger drop" style="font-size: 9px;"><?= $user->frienRequestCount() ?></span>
                <?php endif ?>
            </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-cog"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link"  href="javascript:void(0)" title="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out-alt"></i></a>
            </li>
            <form action="<?= Router::route('handlers.auth.logout') ?>" method="post" id="logout-form">
                <input type="hidden" name="token" value="<?php echo Token::getToken(); ?>">
            </form>
        </ul>
    </div>
</nav>