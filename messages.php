<?php
require_once 'init.php';

use app\Input;
use app\Token;
use app\Config;
use app\Router;
use app\Layouts;
use app\Session;
use app\Redirect;
use Carbon\Carbon;
use app\Auth\Auth;
use app\Model\User;
use app\Model\Message;

if (!Auth::check()) {
    Redirect::to('register');
}
$auth = new User();
$message = new Message();

if (Input::exists('get')) {
    if (Input::get('u') !== '') {
        $data = Input::get('u');
    } else {
        $data = 'new';
    }
} else {
    //get recent message
    //if user logged has no recent msg
    $data = $message->getRecent();
    if (!$data) {
        $data = 'new';
    }
}

if ($data !== 'new') {
    $user = new User($data);
    if (!$user->getUser()) {
        Redirect::to(404);
    }
}

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
                    <div class="box">
                        <div class="box-header with-border p-0 pt-10">
                            <div class="form-element">
                                <input class="form-control px-20" type="text" placeholder="Search Contact" onkeyup="search(this.value)">
                            </div>
                        </div>
                        <div class="box-body p-0">
                            <div class="media-list media-list-hover media-list-divided results">
                                
                            </div>
                        </div>
                    </div>
                <!-- /. box -->
                </div>
                <!-- /.col -->
                <div class="col-xl-9 col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="box">
                                <div class="box-header with-border p-2 pt-10">
                                    Most Recent
                                </div>
                                <div class="box-body p-0">
                                    <div class="media-list media-list-hover media-list-divided">
                                        <?php foreach($message->getConvos() as $key => $value): ?>
                                            <?php $convoUser = new User($key) ?>
                                            <a href="<?= Router::route('messages', ['u' => $convoUser->getUser()->username]) ?>">
                                                <div class="media media-single">
                                                    <img class="avatar avatar-xl" src="<?= $convoUser->getUser()->avatar ?>" alt="...">
                                                    <div class="media-body">
                                                        <!-- 32 -->
                                                        <small><?= $convoUser->getFullName() ?></small>
                                                        <p><?= (strlen($value) < 30) ? $value : substr($value, 0, 24).'...' ?></p>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </div>
                        <!-- /. box -->
                        </div>
                        <!-- /.col -->
                        <?php if($data !== 'new'): ?>
                            <div class="col-lg-8 col-md-12">
                                <div class="box direct-chat">
                                    <div class="box-header with-border">
                                        <h5 class="box-title float-right">Chat Message</h5>
                                        <br>
                                        <h6 class="box-title">You and <a href="<?= $user->getUser()->username ?>"><?= $user->getFullName() ?></a> conversation</h6>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <!-- Conversations are loaded here -->
                                        <div id="chat-app" class="direct-chat-messages chat-app">
                                            <?php foreach($message->retrieve($user->getUser()->id) as $message): ?>
                                                <!-- Message. Default to the left -->
                                                <?php if($message->sender === $user->getUser()->id): ?>
                                                    <div class="direct-chat-msg mb-30">
                                                        <div class="clearfix mb-15">
                                                            <span class="direct-chat-name"><?= $user->getFullName() ?></span>
                                                            <span class="direct-chat-timestamp float-right"><?= Carbon::parse($message->created_at)->diffForHumans() ?></span>
                                                        </div>
                                                        <!-- /.direct-chat-info -->
                                                        <img class="direct-chat-img avatar" src="<?= $user->getUser()->avatar ?>" alt="<?= $user->getUser()->fname ?>">
                                                        <!-- /.direct-chat-img -->
                                                        <div class="direct-chat-text">
                                                            <?= $message->message ?>
                                                        </div>					
                                                        <!-- /.direct-chat-text -->
                                                    </div>
                                                <?php endif ?>
                                                <!-- /.direct-chat-msg -->
                                                <!-- Message to the right -->
                                                <?php if($message->sender === $auth->getUser()->id): ?>
                                                    <div class="direct-chat-msg right mb-30">
                                                        <div class="clearfix mb-15">
                                                            <span class="direct-chat-name float-right">Me</span>
                                                            <span class="direct-chat-timestamp"><?= Carbon::parse($message->created_at)->diffForHumans() ?></span>
                                                        </div>
                                                        <!-- /.direct-chat-info -->
                                                        <img class="direct-chat-img avatar" src="<?= $auth->getUser()->avatar ?>" alt="<?= $auth->getUser()->fname ?>">
                                                        <!-- /.direct-chat-img -->
                                                        <div class="direct-chat-text">
                                                            <?= $message->message ?>
                                                        </div>
                                                        <!-- /.direct-chat-text -->
                                                    </div>
                                                <?php endif ?>
                                                <!-- /.direct-chat-msg -->
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                    <div class="box-footer">
                                        <form autocomplete="off" action="<?= Router::route('handlers.message.chat') ?>" method="post">
                                            <div class="input-group">
                                                <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                                                <input type="hidden" name="token" value="<?= Token::getToken() ?>">
                                                <input type="hidden" name="id" value="<?= $user->getUser()->id ?>">
                                                <input type="hidden" name="username" value="<?= $user->getUser()->username ?>">
                                                <span class="input-group-btn">
                                                    <input type="submit" class="btn btn-primary" value="Send" name="send">
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.box-footer-->
                                </div>
                            <!-- /. box -->
                            </div>
                        <?php endif ?>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
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
	 // chat app scrolling
	  $('#chat-app').slimScroll({
		height: '400px',
        start: 'bottom',
	  });

      function search(value) {
        if (value.trim() === '') {
            return;
        }
        $.post({
            url: "<?= Router::route('handlers.ajax.search-friends')?>",
            data: {value},
            cache: false,
            success(data) {
                if (data === '') {
                    $('.results').text('No results found');
                } else {
                    $('.results').html(data);
                }
            }
        })
        // console.log(value);          
      }
	</script>
</body>