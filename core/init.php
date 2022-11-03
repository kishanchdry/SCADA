<?php 
    session_start();

    $GLOBALS['config']=array(
        'mysql'=>array(
            'host'=>'3.7.237.55',
            'username'=>'root',
            'password'=>'4iTgl2PPONx88jM', // V*ision*123
            'db'=>'scada'
        ),
        'remember'=>array(
            'cookie_name'=>'hash',
            'cookie_expiry'=>644800
        ),
        'session'=>array(
            'session_name'=>'scada',
            'token_name' => 'token'
        )
    );

    spl_autoload_register(function($class){
        require_once 'classes/' .$class. '.php';
    });

    require_once 'functions/sanitize.php';

    if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))){

        $hash = Cookie::get(Config::get('remember/cookie_name'));
        $hashCheck = DB::getInstance()->get('users_sessions', array('hash', '=', $hash));

        if($hashCheck->count()){
            $user = new User($hashCheck->first()->user_id);
            $user->login();

        }

    }

    date_default_timezone_set("Asia/Calcutta");