<?php
mysqli_connect('127.0.0.1', 'roost', '', 'social');
if (mysqli_connect_errno()) {
    echo 'Failed '.mysqli_connect_errno() ;
    var_dump(mysqli_connect_error());
}