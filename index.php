<?php
require_once 'init.php';

use app\Config;
use app\Session;
use app\Redirect;
use app\Auth\Auth;
use app\Layouts;


if (!Auth::check()) {
    Redirect::to('register');
}

$title = 'Social Network';
?>
<?php include_once  Layouts::includes('layouts.head') ?>
<body>
    <?php include_once  Layouts::includes('layouts.nav') ?>
    <div class="container">
        <div class="row">
            <img src="<?= Auth::user()->avatar ?>" alt="<?= Auth::user()->fname ?>">
        </div>
    </div>
    <?php include_once  Layouts::includes('layouts.scripts') ?>
</body>