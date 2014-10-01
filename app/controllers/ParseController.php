<?php
/**
 * Cinemur parse
 * Copyright (c) 2014 Cinémur S.A. All rights reserved.
 */

namespace app\controllers;

use app\models\User;

if (!defined('CRLF')) {
    define('CRLF', "\r\n");
}

class ParseController extends \lithium\action\Controller {

    public function explorer() {

        $users = User::find('all');

        error_log(print_r($users, true));

        return compact('users');
    }

}


