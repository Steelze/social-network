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
    
    <p><?= Auth::user()->fname; ?></p>
    <h1 class="text-danger">Hello Warld</h1>
    <?php include_once  Layouts::includes('layouts.scripts') ?>
</body>