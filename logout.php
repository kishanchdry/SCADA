<?php 
    require_once 'core/init.php';

    $isLoggedIn = new User();

    $isLoggedIn->logout();

    Redirect::to('index.php');