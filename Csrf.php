<?php

//namespace app\core;

class Csrf
{
    const KEY_NAME = "csrf";

    public static function generate()
    {
        $token = password_hash(uniqid(bin2hex(random_bytes(1))), PASSWORD_DEFAULT);
        $_SESSION[self::KEY_NAME] = $token;
        return $token;
    }

    public static function check($token)
    {
        if (empty($_SESSION[self::KEY_NAME])) {
            return false;
        }
        if ($_SESSION[self::KEY_NAME] != $token) {
            return false;
        }
        return true;
    }
}
