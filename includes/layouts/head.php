<?php
    use app\Assets;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= (isset($title)) ? $title : 'Friendly Social'; ?></title>
    <script src="<?= Assets::url('js/all.min.js') ?>"></script>	
    <link rel="stylesheet" href="<?= Assets::url('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= Assets::url('css/style.css') ?>">
    <link rel="stylesheet" href="<?= Assets::url('css/master_style.css') ?>">
</head>