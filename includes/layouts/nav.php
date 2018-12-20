<?php
    use app\Router;
    use app\Auth\Auth;
    use app\Token;
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
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#"><?= Auth::user()->fname ?></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="<?= Router::route('index') ?>"><i class="fa fa-home"></i><span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-envelope"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-bell"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-users"></i></a>
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
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Dropdown
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
    </div>
</nav>